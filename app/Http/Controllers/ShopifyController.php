<?php

namespace App\Http\Controllers;

use App\Jobs\ShopifyUpload;
use App\Models\api\Order;
use App\Models\api\ProductApi;
use App\Models\AutoGenerate;
use App\Models\Client as ModelsClient;
use App\Models\Image;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Product;
use App\Models\Warehouse\Product_warehouse;
use App\Models\ProductOption;
use App\Models\ProductSale;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Shopify;
use App\Models\Sku;
use App\Models\User;
use App\Models\VariantValue;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ShopifyController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function shop()
    {
        $num = rand(10, 100000);

        $url = 'https://speedballcourier.myshopify.com/admin/oauth/authorize?client_id=' . env("SHOPIFY_KEY") . '&scope=write_orders,read_customers,write_products,read_product_listings,write_orders,,write_fulfillments,write_shipping,read_analytics,write_products&redirect_uri=' . env("SHOPIFY_REDIRECT") . '&state=' . $num . '&grant_options[]=per-user';
        // $url = 'https://speedballcourier.myshopify.com/admin/oauth/authorize?client_id=' . env("SHOPIFY_KEY") . '&scope=write_orders,read_customers,write_products,read_product_listings,write_orders,,write_fulfillments,write_shipping,read_analytics,write_products&redirect_uri=' . env("SHOPIFY_REDIRECT") . '&state=' . $num . '&grant_options[]=per-user';
        return redirect($url);
    }

    public function callback_url(Request $request)
    {
        dd($request);
        return redirect('/');
    }

    public function shopify_orders(Request $request)
    {
        $yesterday = Carbon::yesterday();
        $d = $yesterday->toDateTimeLocalString();

        // dd(json_encode($yesterday), '2014-04-25T16:15:47:04:00');
        // 2020-07/orders.json
        // $url = env("SHOPIFY_URL") . '2020-07/orders.json';
        $vendor_id = (Auth::guard("seller")->check()) ? Auth::guard('seller')->id() : $request->vendor_id;
        // $vendor_id = 1;
        // $settings = Shopify::where('vendor_id', $request->vendor_id)->first();
        $settings = new Shopify;
        $url = $settings->url($vendor_id, 'orders.json');
        try {
            $client = new Client();
            $request = $client->request('GET', $url);
            $response = $request->getBody()->getContents();
            $data = json_decode($response);

            $orders = $data->orders;

            $order_transform = new Order;
            $sales = $order_transform->shopify_transform($orders, $vendor_id);


            $order_arr = [];
            foreach ($sales as $order) {
                if ($order['order_no'] !== null) {
                    $real_orders[] = $order;
                    $order_arr[] = $order['order_no'];
                }
            }
            $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
            foreach ($sales as $index => $rep) {
                foreach ($exists_row as $key => $value) {
                    // return $rep['order_no'];
                    if ($rep['order_no'] == $value['order_no']) {
                        Arr::forget($sales, $index);
                    }
                }
            }
            $sales = array_values($sales);

            return $sales;
            return response()->json([
                'sales' => $sales,
                'exist_orders' => $exists_row,
            ], 200);
        } catch (\Exception $e) {
            // dd($e);
            // \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }


    public function shopify_import($data, $user)
    {
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        // return $request->all();
        $this->dispatch(new ShopifyUpload($user, $data));
        return 'Uploaded';
        // $users = User::all();
        // $message = 'New orders uploaded by ' . '<b style="color: red">' . Auth::user()->name . '</b>';
        // // foreach ($users as  $user) {
        // broadcast(new OrderUploadEvent(Auth::user(), $message))->toOthers();
        // Notification::send($users, new OrderUploadNotification(Auth::user(), $message));
        // broadcast(new OrderUploadEvent(Auth::user()))->toOthers();
    }

    public function shopify_pro_import(Request $request)
    {

        $product = new ProductApi;
        // $product->create($request->all());

        // return $request->all();
        /*
        $auto = new AutoGenerate;
        try {
            $product_v = $request->product_upload;
            // return $product_v = $data->products;
            // dd($data);

            foreach ($product_v as $key =>  $data) {
                // if ($key > 4) {
                //     return;
                // }
                // return $data;
                $product = Product::updateOrCreate([
                    'product_name' => $data['title']
                ], [

                    'user_id' => $this->logged_user()->id,
                    'vendor_id' => $request->vendor_id,
                    'sku_no' => $auto->auto_generate('products', 'sku_no', 'SKU_')
                ]);


                // return $id = IdGenerator::generate(['table' => 'sales', 'field' => 'order_no', 'length' => 10, 'prefix' =>'INV-']);
                //output: INV-000001
                // $sku = $auto->auto_generate('products', 'sku_no', 'SKU_');

                $product_data = $data['variants'][0];

                Sku::updateOrCreate(
                    [
                        'sku_no' => $product['sku_no']
                    ],
                    [
                        'buying_price' => $product_data['price'],
                        'price' => $product_data['price'],
                        'quantity' => $product_data['inventory_quantity'],
                        'product_id' => $product->id,
                        'reorder_point' => 100
                    ]
                );

                // return $item['price'];

                Product_warehouse::updateOrCreate(
                    [
                        'warehouse_id' => $request->warehouse_id,
                        'product_id' => $product->id,
                        'seller_id' => $request->vendor_id
                    ],
                    [
                        'onhand' => $product_data['inventory_quantity'],
                        'available_for_sale' => $product_data['inventory_quantity'],
                        'user_id' => $this->logged_user()->id
                    ]
                );


                $relation = new VariantController;
                $update_product = Product::find($product->id);
                $update_product->virtual = true;
                $update_product->active = true;
                $update_product->save();
                // $faker->array
                // $relation->category_fun($category, $update_product);
                // $relation->subcategory_fun(shuffle(array(1, 2, 3, 4, 5, 6, 7, 8, 9)), $update_product);
                // $relation->brand_fun(shuffle(array(1, 2, 3, 4, 5, 6, 7, 8, 9)), $update_product);
                // return $update_product;

                $image = Image::where('product_id', $product->id)->first();
                if (!$image) {
                    $image = new Image();
                }
                $image->image = $data['image']['src'];
                $image->display = true;
                $image->product_id = $product->id;
                $image->save();

                $product_update = Product::find($product->id);
                $product_update->update_comment = 'Shopify Product created by ' . $this->logged_user()->id;
                $product_update->save();
            }

            // return $orders = $data->products;
            // return $orders;
        } catch (Exception $e) {
            //throw $th;
            \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
        */
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function shopify_products(Request $request)
    {

        // return $request->all();
        $vendor_id = $request->vendor_id;

        $settings = new Shopify;
        $url = $settings->url($vendor_id, 'products.json');
        // $url = 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/products.json';
        $auto = new AutoGenerate;

        try {
            $client = new Client;
            $request = $client->request('GET', $url);
            $response = $request->getBody()->getContents();


            $data_items = json_decode($response);
            // dd($data_items->products);

            $product = new ProductApi;
            return $product->shopify_product($data_items);

            // return $sku_restore;
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function shopify_no_variant($request)
    {
        // dd($request->product->variants[0]->price);

        $user = $this->logged_user();

        // return $request->all();
        // $sku_values = $request->sku_values;
        // $product = $request->product;
        $variants = $request->variants;

        // dd($variants->id);

        $product = new Product;
        // $product = Product::find(32);
        // $product->description = $data->product->description;

        $product->product_name = $request->title;
        $product->user_id = 1;
        $product->vendor_id = 1;
        // $sku_no = new AutoGenerate;
        $product->sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
        $product->save();

        // return $request['subcategories'];
        Sku::updateOrCreate(
            [
                'sku_no' => $product['sku_no']
            ],
            [
                'buying_price' => $request->variants[0]->price,
                'price' => $request->variants[0]->price,
                'quantity' => 0,
                'product_id' => $product->id,
                'reorder_point' => 0
            ]
        );



        $relation = new VariantController;
        $update_product = Product::find($product->id);
        $update_product->virtual = $product->virtual;
        $update_product->active = $product->active;
        $update_product->save();

        // $relation->category_fun($request->product['categories'], $update_product);
        // $relation->subcategory_fun($request->product['subcategories'], $update_product);
        // $relation->brand_fun($request->product['brands'], $update_product);
        // return $update_product;

        $product_update = Product::find($product->id);
        $product_update->update_comment = 'Product updated by ' . $user->name;
        $product_update->vendor_id = $product->vendor_id;
        $product_update->save();
        // return 'success';
    }
}
