<?php

namespace Database\Seeders;

use App\Http\Controllers\VariantController;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Warehouse\Product_warehouse;
use App\Models\Sku;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dispatcher = Product::getEventDispatcher();
        Product::unsetEventDispatcher();

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Placeholder($faker));
        foreach (range(1, 4) as $index) {
            $product =  Product::create([
                'user_id' => $faker->numberBetween($min = 1, $max = 5),
                'vendor_id' => $faker->numberBetween($min = 1, $max = 1),
                'product_name' => $faker->productName,
                'sku_no' => 'SKU-' . $faker->numberBetween($min = 1000, $max = 10000)
                    // 'description' => $faker->text,
                // 'pos_status' => $faker->word,
                // 'price' => $faker->numberBetween($min = 400, $max = 10000),
                // 'product_barcode' => $faker->ean8,
                // 'quantity' => $faker->numberBetween($min = 1, $max = 20),
                // 'sku' => $faker->isbn10,
                // 'tax_category_id' => $faker->numberBetween($min = 1, $max = 200),
                // 'tax_percent' => $faker->numberBetween($min = 1, $max = 20),
                // 'type' => $faker->word,
                // 'weight' => $faker->numberBetween($min = 1, $max = 20),
                // 'image' => $faker->placeholder(),
            ]);
            $products[] = $product;
        }


        foreach ($products as  $product) {


            // return $request->all();
            // return $request->product['subcategories'];
            Sku::updateOrCreate(
                [
                    'sku_no' => $product->sku_no
                ],
                [
                    'buying_price' => $faker->numberBetween($min = 1000, $max = 10000),
                    'price' => $faker->numberBetween($min = 1000, $max = 10000),
                    'quantity' => $faker->numberBetween($min = 1, $max = 10),
                    'product_id' => $product->id,
                    'reorder_point' => $faker->numberBetween($min = 100, $max = 1000)
                ]
            );

            $warehouse_arr = $product['warehouse_arr'];
            // return $item['price'];

            Product_warehouse::updateOrCreate(
                [
                    'warehouse_id' => $faker->numberBetween($min = 1, $max = 2),
                    'product_id' => $product->id,
                    'seller_id' => $product->vendor_id
                ],
                [
                    'onhand' => $faker->numberBetween($min = 100, $max = 1000),
                    'available_for_sale' => $faker->numberBetween($min = 100, $max = 1000),
                    'user_id' => $faker->numberBetween($min = 1, $max = 2)
                ]
            );

            $category = Category::inRandomOrder()->take(2)->get();

            $relation = new VariantController;
            $update_product = Product::find($product->id);
            $update_product->virtual = $faker->boolean();
            $update_product->active = $faker->boolean();
            $update_product->save();
            // $faker->array
            $relation->category_fun($category, $update_product);
            // $relation->subcategory_fun(shuffle(array(1, 2, 3, 4, 5, 6, 7, 8, 9)), $update_product);
            // $relation->brand_fun(shuffle(array(1, 2, 3, 4, 5, 6, 7, 8, 9)), $update_product);
            // return $update_product;

            $image = Image::where('product_id', $product->id)->first();
            if (!$image) {
                $image = new Image();
            }
            $image->image = $faker->imageUrl($width = 640, $height = 480);
            $image->display = true;
            $image->product_id = $product->id;
            $image->save();

            $product_update = Product::find($product->id);
            $product_update->update_comment = 'Product updated by Jimmy';
            $product_update->save();
        }

        Product::setEventDispatcher($dispatcher);
    }
}
