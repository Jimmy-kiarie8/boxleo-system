<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Jobs\ShopifyUpload;
use App\Uploads\OrderUpload;
use App\Models\api\Order;
use App\Models\Sale;
use App\Models\Shopify;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Shopify\ShopifyService;
use App\Shopify\Webhook;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $warehouse = Warehouse::where('ou_id', $shopify->ou_id)->latest()->first();
        $warehouse_id = $warehouse->id;
        $data = ['vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id, 'shop' => $shopify];

        $shop_product = new Shopify();
        $products = $shop_product->shopify_products(new Request($data));
        $shop = new Shopify();
        $shop->product($products, $vendor_id, $warehouse_id);
    }

    public function sync_orders(Request $request)
    {
        // return $request->all();
        $shopify = Shopify::find($request->id);
        $vendor_id = $shopify->vendor_id;
        // $warehouse_id = 1;
        $warehouse = Warehouse::where('ou_id', $shopify->ou_id)->latest()->first();
        $warehouse_id = $warehouse->id;
        $data = ['vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id, 'shop' => $shopify];
        $shop_orders = new Shopify();
        $orders = $shop_orders->shopify_orders(new Request($data));

        // $test = new Test();
        // $test->test = serialize($orders);
        // $test->save();
        $data = ['orders' => $orders, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
        ShopifyUpload::dispatch($data, $user, $shopify, 'Shopify');
    }

    public function shopify_status(Request $request, $id)
    {
        $shop = Shopify::find($id);
        $shop->active = !$request->active;
        $shop->save();

        $webhook = new Webhook;
        $webhook->webhook_deactivate($id);
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

    public function shopify_webhook($vendor_id)
    {
        $settings = new Shopify;
        $url = $settings->url($vendor_id, 'webhooks.json');
        $client = new Client();

        $data = [
            "topic" => "orders/create",
            "address" => env('APP_URL', '.logixsaas.com') . '/api/shop_order_webhook/?vendor=' . $vendor_id,
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




    public function close_order($id)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $url = 'https://' .  $shopify->shopify_url;
        $token = $shopify->shopify_secret;
        $shop_orders = new ShopifyService();
        return $shop_orders->closeOrder($orderId, $url, $token);
    }
    public function update_order($id)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $url = 'https://' .  $shopify->shopify_url;
        $token = $shopify->shopify_secret;
        $shop_orders = new ShopifyService();
        return $shop_orders->updateOrder($orderId, $url, $token);
    }
    public function payment_order($id)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $url = 'https://' .  $shopify->shopify_url;
        $token = $shopify->shopify_secret;
        $shop_orders = new ShopifyService();
        return $shop_orders->updateOrder($orderId, $url, $token);
    }

    public function fulfill_order($id)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id', 'order_no')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $url = 'https://' .  $shopify->shopify_url;
        $token = $shopify->shopify_secret;
        $shop_orders = new ShopifyService();
        $fulfillment_id = $shop_orders->updateFulfillment($orderId, $url, $token);

        if(!$fulfillment_id) {
            return false;
        }
        // return $fulfillment_id;

        // Fulfill an order
        $response = $shop_orders->fulfill($fulfillment_id, $url, $token, $order->order_no);
        return $response;
        

    }
}
