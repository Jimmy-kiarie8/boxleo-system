<?php

namespace App\Models\api;

use App\Http\Controllers\VariantController;
use App\Models\Image;
use App\Models\Product;
use App\Models\Warehouse\Product_warehouse;
use App\Models\Sku;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProductApi
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function transform_product($products)
    {

        $product_arr = [];
        $all_products = [];

        foreach ($products as $product) {
            $product_arr['product_name'] = $product->name;
            $product_arr['price'] = $product->price;


            $product_arr['buying_price'] = $product->price;
            $product_arr['quantity'] = 0;
            $product_arr['onhand'] = 0;
            $product_arr['reorder_point'] = 0;
            $product_arr['active'] = 0;
            $all_products[] = $product_arr;
        }
        return $all_products;
    }
    // public function shopify_product($products_)
    // {
    //     $product_arr = [];
    //     $all_products = [];

    //     foreach ($products_->products as $key => $product) {
    //         $product_arr['product_name'] = $product->title;
    //         $product_arr['price'] = $product->variants[0]->price;
    //         $product_arr['buying_price'] = $product->variants[0]->price;
    //         $product_arr['quantity'] = 0;
    //         $product_arr['onhand'] = 0;
    //         $product_arr['reorder_point'] = 0;
    //         $product_arr['image'] = ($product->image) ? $product->image->src : '/images/notfound.png';
    //         $product_arr['active'] = 0;
    //         $all_products[] = $product_arr;
    //     }
    //     return $all_products;
    // }

    public function shopify_product($products_)
    {
        $allProducts = [];

        foreach ($products_->products as $product) {
            foreach ($product->variants as $variant) {
                $productData = [
                    'product_name' => $product->title . ' ' . $variant->title,
                    'price' => $variant->price,
                    'sku' => $variant->sku,
                    'buying_price' => $variant->price,
                    'quantity' => 0,
                    'onhand' => 0,
                    'reorder_point' => 0,
                    'image' => ($product->image) ? $product->image->src : '/images/notfound.png',
                    'active' => 0,
                ];

                $allProducts[] = $productData;
            }
        }

        // Now you have an array of product data for each variant
        // You can use this data to create products in your Laravel app's database

        return $allProducts;
    }


    public function create($product, $vendor_id, $warehouse_id, $user)
    {
        if (array_key_exists('sku', $product)) {    
            $product_exists = Product::where('sku_no', $product['sku'])->where('vendor_id', $vendor_id)->exists();
            $sku = $product['sku'];
        } else {
            $sku = make_reference_id('SKU', Sku::max('id') + 1);
            $product_exists = Product::where('product_name', $product['product_name'])->where('vendor_id', $vendor_id)->exists();
        }
        if (!$product_exists) {
            $product_ = new Product;
            $product_->product_name = $product['product_name'];
            $product_->vendor_id = $vendor_id;
            $product_->user_id = $user['id'];
            $product_->virtual = true;
            $product_->active = true;
            $product_->sku_no = $sku;
            // $product_->sku_no = IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
            $product_->save();

            Sku::updateOrCreate(
                [
                    'sku_no' => $product_->sku_no
                ],
                [
                    'buying_price' => $product['price'],
                    'price' => $product['price'],
                    'quantity' => 0,
                    'product_id' => $product_->id,
                    'reorder_point' => 0
                ]
            );

            /* Product_warehouse::updateOrCreate(
                [
                    'warehouse_id' => $warehouse_id,
                    'product_id' =>  $product_->id,
                    'seller_id' => $vendor_id
                ],
                [
                    'onhand' => 0,
                    'available_for_sale' => 0,
                    'user_id' => $user['id']
                ]
            ); */

            if (array_key_exists('image', $product)) {
                $image = new Image;
                $image->image = $product['image'];
                $image->display = true;
                $image->product_id = $product_->id;
                $image->save();
            }
            return $product_;
        }
    }

    public function update($request, $id)
    {

        $user = $this->logged_user();

        // return $request->all();
        // $sku_values = $request->sku_values;
        $product = $request->product;
        // return $request->product['subcategories'];
        Sku::updateOrCreate(
            [
                'sku_no' => $product['sku_no']
            ],
            [
                'buying_price' => $product['buying_price'],
                'price' => $product['price'],
                'quantity' => $product['onhand'],
                'product_id' => $id,
                'reorder_point' => $product['reorder_point']
            ]
        );

        $warehouse_arr = $product['warehouse_arr'];
        foreach ($warehouse_arr as  $item) {
            // return $item['price'];

            Product_warehouse::updateOrCreate(
                [
                    'warehouse_id' => $item['warehouse_id'],
                    'product_id' => $id,
                    'seller_id' => $product['vendor_id']
                ],
                [
                    'onhand' => $item['onhand'],
                    'available_for_sale' => $item['onhand'],
                    'user_id' => $user->id
                ]
            );
        }

        $relation = new VariantController;
        $update_product = Product::find($id);
        $update_product->virtual = $product['virtual'];
        $update_product->active = $product['active'];
        $update_product->save();

        $relation->category_fun($request->product['categories'], $update_product);
        $relation->subcategory_fun($request->product['subcategories'], $update_product);
        $relation->brand_fun($request->product['brands'], $update_product);
        // return $update_product;

        $product_update = Product::find($id);
        $product_update->active = $product['active'];
        $product_update->product_name = $product['product_name'];
        $product_update->update_comment = 'Product updated by ' . $user->name;
        $product_update->vendor_id = $product['vendor_id'];
        $product_update->save();
        return 'success';
    }
}
