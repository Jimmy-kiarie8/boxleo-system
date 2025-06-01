<?php

namespace App\Models;

use App\Jobs\ProductUpload;
use App\Models\api\Order;
use App\Models\api\ProductApi;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Shopify extends Model
{
    use HasFactory;

    protected $fillable  = [
        'shopify_name', 'shopify_key', 'shopify_secret', 'shopify_url', 'active', 'auto_sync', 'order_webhook', 'product_webhook', 'vendor_id', 'sync_interval', 'order_prefix', 'last_order_synced', 'sync_option', 'last_product_synced', 'ou_id', 'new_api', 'webhook_id'
    ];


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    public function url($vendor_id, $end_point)
    {
        $settings = Shopify::where('vendor_id', $vendor_id)->first();
        if (!$settings) {
            abort(422, 'Vendor shop is not connected to the app');
        }
        $created_at_min = '';
        if ($end_point == 'orders.json') {
            $created_at_min = $settings->last_order_synced;
        } elseif ($end_point == 'products.json') {
            $created_at_min = $settings->last_product_synced;
        }
        return 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/' . $end_point . '?created_at_min=' . $created_at_min;
    }
    public function connect($data)
    {
        // $this->authorize('create', Shopify::class);


        $prefix = substr($data['shopify_name'], 0, 3);
        $shopify = new Shopify();
        $shopify->shopify_name = (array_key_exists('shopify_name', $data)) ? $data['shopify_name'] : null;
        $shopify->shopify_key = (array_key_exists('shopify_key', $data)) ? $data['shopify_key'] : null;
        $shopify->shopify_secret = (array_key_exists('shopify_secret', $data)) ? $data['shopify_secret'] : null;
        $shopify->shopify_url = (array_key_exists('shopify_url', $data)) ? $data['shopify_url'] : null;
        $shopify->active = true;
        $shopify->auto_sync = true;
        $shopify->order_webhook = true;
        $shopify->product_webhook = true;
        $shopify->vendor_id = $data['vendor_id'];
        $shopify->ou_id = Auth::user()->ou_id;
        $shopify->order_prefix = strtoupper($prefix);
        $shopify->save();
        return $shopify;
    }

    public function product_transform($products)
    {
        // dd($products);
        $product_arr = [];
        $all_products = [];

        foreach ($products->products as $product) {
            // dd($product->variants[0]->price);
            $product_arr['product_name'] = $product->title;
            $product_arr['price'] = $product->variants[0]->price;

            $product_arr['buying_price'] = $product->variants[0]->price;
            $product_arr['quantity'] = 0;
            $product_arr['onhand'] = 0;
            $product_arr['reorder_point'] = 0;
            $product_arr['active'] = 0;
            $product_arr['image'] =  $product->image->src;
            $all_products[] = $product_arr;
        }
        return $all_products;
    }

    public function product($products, $vendor_id, $warehouse_id)
    {
        // $shop_api = new Product();
        // $product_ = $shop_api->product_create($products);
        // $user = $this->logged_user();
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        $data = ['products' => $products, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        ProductUpload::dispatch($data, $user);
    }

    public function webhook($data)
    {
    }
    // public function shopify_products(Request $request)
    // {

    //     // return $request->all();
    //     $vendor_id = $request->vendor_id;

    //     $settings = new Shopify;
    //     $url = $settings->url($vendor_id, 'products.json');
    //     // $url = 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/products.json';
    //     $auto = new AutoGenerate;

    //     try {
    //         $client = new Client();
    //         $request = $client->request('GET', $url);
    //         $response = $request->getBody()->getContents();


    //         $data_items = json_decode($response);
    //         // dd($data_items->products);

    //         $product = new ProductApi;
    //         return $product->shopify_product($data_items);

    //         // return $sku_restore;
    //     } catch (Exception $e) {
    //         Log::debug($e);
    //         dd($e);
    //     }
    // }


    public function shopify_products(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $shop = $request->shop;
        $new_api = $shop->new_api;

        try {
            if ($new_api) {
                $url = 'https://' . $shop->shopify_url . '/admin/api/2023-07/products.json';
                $token = $shop->shopify_secret;

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $token,
                ])->get($url)->body();
            } else {
                $settings = new Shopify;
                $url = $settings->url($vendor_id, 'products.json');


                $response = Http::get($url)->body();
            }

            $data_items = json_decode($response);
            // Log::debug($data_items->products);

            $product = new ProductApi;
            return $product->shopify_product($data_items);
        } catch (\Exception $e) {
            // Handle exceptions or log errors as needed
            // Log::error($e);
            // dd($e);
        }
    }
    public function shopify_orders(Request $request)
    {
        $created_at_min = Carbon::now()->subDays(1)->toDateTimeLocalString();

        // $yesterday = Carbon::yesterday();
        // $d = $yesterday->toDateTimeLocalString();

        // dd(json_encode($yesterday), '2014-04-25T16:15:47:04:00');
        // 2020-07/orders.json
        // $url = env("SHOPIFY_URL") . '2020-07/orders.json';
        $vendor_id = (Auth::guard("seller")->check()) ? Auth::guard('seller')->id() : $request->vendor_id;
        // $vendor_id = 1;
        // $settings = Shopify::where('vendor_id', $request->vendor_id)->first();

        $shop = $request->shop;
        $new_api = $shop->new_api;
        try {
            if ($new_api) {

                $url = 'https://' .  $shop->shopify_url . '/admin/api/2022-01/orders.json';


                if ($created_at_min) {
                    $url = $url . '?created_at_min=' . $created_at_min;
                }
                $token = $shop->shopify_secret;
                // Make the HTTP request
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $token,
                ])->get($url);

                // Get the response body
                $response = $response->body();
                // echo $response;
            } else {

                $settings = new Shopify;
                $url = $settings->url($vendor_id, 'orders.json');
                $client = new Client();
                $request = $client->request('GET', $url);
                $response = $request->getBody()->getContents();

                $response = json_decode($response);
            }
            // return $response;
              $data = json_decode($response);


            $orders = $data->orders;
            // return ($orders);

            $order_transform = new Order;
            // Log::debug($orders);

            // $sales = $order_transform->shopify_transform($orders, $vendor_id);
            if ($new_api) {
                $sales = $order_transform->shopify_transform_arr($orders, $vendor_id);
            } else {
                $sales = $order_transform->shopify_transform($orders, $vendor_id);
            }

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
            // Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    // public function shopify_orders(Request $request)
    // {
    //     $yesterday = Carbon::yesterday();
    //     $d = $yesterday->toDateTimeLocalString();

    //     // dd(json_encode($yesterday), '2014-04-25T16:15:47:04:00');
    //     // 2020-07/orders.json
    //     // $url = env("SHOPIFY_URL") . '2020-07/orders.json';
    //     $vendor_id = (Auth::guard("seller")->check()) ? Auth::guard('seller')->id() : $request->vendor_id;
    //     // $vendor_id = 1;
    //     // $settings = Shopify::where('vendor_id', $request->vendor_id)->first();
    //     $settings = new Shopify;
    //     $url = $settings->url($vendor_id, 'orders.json');
    //     try {
    //         $client = new Client();
    //         $request = $client->request('GET', $url);
    //         $response = $request->getBody()->getContents();
    //         $data = json_decode($response);

    //         $orders = $data->orders;

    //         $order_transform = new Order;
    //         $sales = $order_transform->shopify_transform($orders, $vendor_id);


    //         $order_arr = [];
    //         foreach ($sales as $order) {
    //             if ($order['order_no'] !== null) {
    //                 $real_orders[] = $order;
    //                 $order_arr[] = $order['order_no'];
    //             }
    //         }
    //         $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
    //         foreach ($sales as $index => $rep) {
    //             foreach ($exists_row as $key => $value) {
    //                 // return $rep['order_no'];
    //                 if ($rep['order_no'] == $value['order_no']) {
    //                     Arr::forget($sales, $index);
    //                 }
    //             }
    //         }
    //         $sales = array_values($sales);

    //         return $sales;
    //         return response()->json([
    //             'sales' => $sales,
    //             'exist_orders' => $exists_row,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // dd($e);
    //         // Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
    //         return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
    //     }
    // }
    /* 
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
            Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    } */
}
