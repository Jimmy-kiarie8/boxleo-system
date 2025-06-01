<?php

namespace App\Http\Controllers\Marketplace_;

use App\Http\Controllers\Controller;
use App\Jobs\ShopifyUpload;
use App\Models\api\Order;
use App\Models\api\Woocommerce as ApiWoocommerce;
use App\Models\Sale;
use App\Models\Shopify;
use App\Models\User;
use App\Models\Woocommerce;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class WoocommerceController extends Controller
{
    public function woocommerce_stores()
    {
        return Woocommerce::all();
    }

    public function shopify_settings(Request $request, $id)
    {
        // return $request->all();

        $data = [
            'auto_sync' => $request->auto_sync,
            'order_webhook' => $request->order_webhook,
            'product_webhook' => $request->product_webhook,
            'sync_interval' => $request->sync_interval,
            'sync_option' => $request->sync_option,
            'ou_id' => $request->ou_id
        ];
        return Woocommerce::find($id)->update($data);
    }

    public function woocommerce_status(Request $request, $id)
    {
        // return $request->all();
        $shop = Woocommerce::find($id);
        $shop->active = !$request->active;
        $shop->save();
    }
    public function woocommerce_auth($vendor_id)
    {
        // return 'https://' . tenant('id') . env('CENTRAL_DOMAIN');
        $store_url = 'https://woocommerce-317638-2025580.cloudwaysapps.com';
        $endpoint = '/wc-auth/v1/authorize';
        $url = $this->remove_http($store_url);

        $data = ['store_url' => $url, 'vendor' => $vendor_id, 'user_id' => Auth::user()->id];

        // $encrypt = Crypt::encrypt($data);
        $encrypt = base64_encode(serialize($data));
        $callback_url =  'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/woocommerce_callback?store=' . $encrypt;
        // return $callback_url =  'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/woocommerce_callback/' . $encrypt;

        $params = [
            'app_name' => 'Logixsaas',
            'scope' => 'read_write',
            'user_id' => 123,
            'return_url' =>  'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/woocommerce_return',
            'callback_url' =>  $callback_url
        ];
        $query_string = http_build_query($params);
        return $store_url . $endpoint . '?' . $query_string;

        return Redirect::to($store_url . $endpoint . '?' . $query_string);

        dd($store_url . $endpoint . '?' . $query_string);
    }

    public function remove_http($url)
    {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
    public function woocommerce_return()
    {
        return;
    }

    public function woocommerce_callback(Request $request)
    // public function woocommerce_callback(Request $request, $store)
    {
        // $store = Crypt::decrypt($store);
        $store = unserialize(base64_decode($request->store));
        // return $request->all();
        $vendor_id = $store['vendor'];
        $user_id = $store['user_id'];
        $url = $store['store_url'];
        $data = [
            'woocommerce_url' => $url,
            'woocommerce_consumer_secret' => $request->consumer_secret,
            'woocommerce_consumer_key' => $request->consumer_key,
            'woocommerce_name' =>  $url,
            'vendor_id' => $vendor_id,
            'user_id' => $user_id
        ];

        $create = new Woocommerce;
        $create->connect($data);
        return true;
    }
    public function woocommerce_orders()
    // public function woocommerce_orders(Request $request)
    {
        // return $request->all();
        /*  $start_date = $request->start_date;
        $end_date = $request->end_date; */

        $shop = Woocommerce::first();
        // $woocommerce = Woocommerce::find($request->id);
        $vendor_id = $shop->vendor_id;
        $warehouse_id = 1;
        // $vendor_id = $request->vendor_id;
        $woocommerce = new ApiWoocommerce;
        $orders = $woocommerce->orders('', '', $vendor_id);
        $order = new Woocommerce();
        $sales = $order->transform_order($orders, $vendor_id);

        $order_arr = [];
        foreach ($sales as $order) {
            if ($order['order_no'] !== null) {
                $real_orders[] = $order;
                $order_arr[] = $order['order_no'];
            }
        }
        /* $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        } */
        $sales = array_values($sales);


        $data = ['orders' => $sales, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];

        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();



        ShopifyUpload::dispatch($data, $user, $shop, 'Woocommerce');
        return $sales;
        /* 
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200); */
    }


    // public function sync_orders()
    public function sync_orders(Request $request)
    {
        // $woocommerce = Woocommerce::first();
        $woocommerce = Woocommerce::find($request->id);
        $vendor_id = $woocommerce->vendor_id;
        $woo_orders = new ApiWoocommerce;
        $woo_orders = $woo_orders->orders('', '', $vendor_id);


        $order = new Woocommerce();
        $sales = $order->transform_order($woo_orders, $vendor_id);
        $warehouse_id = 1;
        $data = ['orders' => $sales, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        ShopifyUpload::dispatch($data, $user, $woocommerce, 'Woocommerce');
    }

    // public function sync_products()
    public function sync_products(Request $request)
    {

        $woocommerce = Woocommerce::find($request->id);
        // $woocommerce = Woocommerce::first();
        $vendor_id = $woocommerce->vendor_id;
        $woo_products = new ApiWoocommerce;
        $woo_products = $woo_products->products('', '', $vendor_id);

        $woo_shop = new Woocommerce();
        $products = $woo_shop->transform_product($woo_products);

        $vendor_id = $woocommerce->vendor_id;
        $warehouse_id = 1;
        $shop = new Shopify();
        $shop->product($products, $vendor_id, $warehouse_id);
    }


    public function woocommerce_webhook()
    // public function woocommerce_webhook(Request $request)
    {
        $vendor_id = 1;
        $url = tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/webhook_orders?vendor=' . $vendor_id;
        $data = [
            'name' => 'Order updated',
            'topic' => 'order.updated',
            'delivery_url' => 'https://' . $url
        ];

        $woocommerce = new ApiWoocommerce;
        return $woocommerce->webhook($data, $vendor_id);
    }
    public function webhook_list(Request $request)
    {
        $woocommerce = new ApiWoocommerce;
        $id = 1;
        return $woocommerce->webhook_list($id);
    }

    public function webhook_orders(Request $request)
    {
        $vendor_id = $request->vendor;
        $woocommerce = Woocommerce::where('vendor_id', $vendor_id)->first();
        $woo_orders = ['orders' => $request->all()];
        $order = new Woocommerce();
        $sales = $order->transform_order($woo_orders, $vendor_id);
        $warehouse_id = 1;
        $data = ['orders' => $sales, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        ShopifyUpload::dispatch($data, $user, $woocommerce, 'Woocommerce');
    }
}
