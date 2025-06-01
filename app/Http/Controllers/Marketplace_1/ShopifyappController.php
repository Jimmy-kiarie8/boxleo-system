<?php

namespace App\Http\Controllers\Marketplace_;

use App\Http\Controllers\Controller;
use App\Jobs\ShopifyUpload;
use App\Uploads\OrderUpload;
use App\Models\api\Order;
use App\Models\Sale;
use App\Models\Shopify;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ShopifyappController extends Controller
{
    public function shopify_stores()
    {
        return Shopify::all();
    }


    public function sync_products(Request $request)
    {
        // return $request->all();
        $shopify = Shopify::find($request->id);

        $vendor_id = $shopify->vendor_id;
        $warehouse_id = 1;
        $data = ['vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];

        $shop_product = new Shopify();
        $products = $shop_product->shopify_products(new Request($data));
        $shop = new Shopify();
        $shop->product($products, $vendor_id, $warehouse_id);
    }

    public function sync_orders(Request $request)
    {
        $shopify = Shopify::find($request->id);
        $vendor_id = $shopify->vendor_id;
        $warehouse_id = 1;
        $data = ['vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $shop_orders = new Shopify();
        $orders = $shop_orders->shopify_orders(new Request($data));
        $data = ['orders' => $orders, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        ShopifyUpload::dispatch($data, $user, $shopify, 'Shopify');
    }

    public function shopify_status(Request $request, $id)
    {
        // return $request->all();
        $shop = Shopify::find($id);
        $shop->active = !$request->active;
        $shop->save();
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
        return Shopify::find($id)->update($data);
    }

    public function shopify_webhook()
    {
        $vendor_id = 1;
        $settings = new Shopify;
        $url = $settings->url($vendor_id, 'webhooks.json');
        $client = new Client();

        $data = [
            "topic" => "orders/create",
            "address" => 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/shop_order_webhook/?vendor=' . $vendor_id,
            "format" => "json"
        ];
        $webhook = ['webhook' => $data];

        try {
            $response = $client->request('POST', $url, [
                // 'headers' => [
                //     'Content-Type' => 'application/x-www-form-urlencoded',
                // ],
                'form_params' => $webhook
            ]);
            return $response;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function shopify_webhook_delete()
    {
        $vendor_id = 2;
        $settings = new Shopify;
        return $url = $settings->url($vendor_id, 'webhooks/' . 1022172102734 . '.json');
        $client = new Client();

        try {
            $response = $client->request("DELETE", $url);
            return $response;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function app_shop()
    {
        $shop = Shopify::first('data');
        return $data = new Request(unserialize($shop->data));
        return $data->shipping_address;
    }


    // public function shop_order_webhook()
    public function shop_order_webhook(Request $request)
    {
        /*  Log::debug($request->all());
        $data_ = serialize($request->all());

        $shopify_ = new Shopify();
        $shopify_->shopify_name = 'App';
        $shopify_->shopify_key = 'App';
        $shopify_->shopify_secret ='App';
        $shopify_->shopify_url = 'App';
        $shopify_->active = true;
        $shopify_->auto_sync = true;
        $shopify_->order_webhook = true;
        $shopify_->product_webhook = true;
        $shopify_->vendor_id = 1;
        $shopify_->order_prefix = 'App';
        $shopify_->data = $data_;
        $shopify_->save();
 */


        $vendor_id = 1;
        // $request = $this->app_shop();
        // $vendor_id = $request->vendor;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $warehouse_id = 1;
        $order_transform = new Order;
        $orders = ['orders' => $request->all()];
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

        // return $sales;


        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        $data = ['orders' => $sales, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        // $this->shopify_import($data, $user);

        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();

        ShopifyUpload::dispatch($data, $user, $shopify, 'Shopify');
    }
}
