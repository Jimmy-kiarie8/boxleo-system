<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProductUpload;
use App\Models\api\Order;
use App\Models\api\ProductApi;
use App\Models\api\Woocommerce;
use App\Models\AutoGenerate;
use App\Models\Client as ModelsClient;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Sku;
use App\Models\User;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class woocommerceController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function woocommerce_(Request $request)
    {
        // Log::debug($request->all());


        $api_ = DB::table('api_test')->first();

        $data = unserialize($api_->item);

        // DB::table('api_test')->insert(['item' => serialize($request->all())]);
        // return;

        $billing = $data['billing'];
        $shipping = $data['shipping'];

        $client_name = (array_key_exists('first_name', $billing)) ? $billing['first_name'] . ' ' . $billing['last_name'] : $shipping['first_name'] . ' ' . $shipping['last_name'];
        $client_address = (array_key_exists('address_1', $billing)) ? $billing['address_1'] : $shipping['address_1'];
        $client_city = (array_key_exists('city', $billing)) ? $billing['city'] : $shipping['city'];
        $client_phone = (array_key_exists('phone', $billing)) ? $billing['phone'] : $shipping['phone'];
        $client_email = (array_key_exists('email', $billing)) ? $billing['email'] : $shipping['email'];

        $products = $data['line_items'];

        $client = Client::updateOrCreate(
            [
                'name' => $client_name,
                'phone' => $client_phone
            ],
            [
                'address' => $client_address,
                'email' => $client_email,
                'user_id' => Auth::id()
            ]
        );

        $sale = new Sale;

        $auto = new AutoGenerate;
        $order_no = $auto->auto_generate('sales', 'order_no', 'ORD-');
        // $order_no =
        $sale->order_no = $order_no;
        // $sale->total_price = $data['total'];
        // $sale->sub_total = $data['sub_total'];
        // $sale->total_price = $data['total_price'] - $data['adjustment'];
        $sale->total_price = $data['total'];
        $sale->sub_total = $data['total'];
        // $sale->discount = $discount;
        $sale->user_id = 1;
        // $sale->user_id = $this->logged_user()->id;
        $sale->client_id = $client->id;
        // $sale->drawer_id = $drawers->id;
        // $sale->order_no = $data['price'];
        // $sale->delivery_date = $data['delivery_date'];
        $sale->customer_notes = $data['customer_note'];
        // $sale->shipping_charges = ($data['shipping_charges']) ? $data['shipping_charges'] : 0;
        // $sale->order_no = $data['order_no'];

        $sale->ou_id = 1;

        // if ($data['pos'] == 'POS') {
        $sale->warehouse_id = 1;
        // } else {
        //     $sale->warehouse_id = $data['warehouse_id'];
        // }

        $sale->save();

        foreach ($products as $product_item) {

            $product = Product::where('product_name', 'LIKE', "%{$product_item['name']}%")->first();
            $product['quantity'] = $product_item['quantity'];

            // return($product['vendor_id']);
            $sku_id = Sku::where('sku_no', $product['sku_no'])->first('id');
            // return $sku_id;
            $product_sale = new ProductSale;
            $product_sale->sale_id = $sale->id;
            $product_sale->product_id = $product['id'];
            $product_sale->seller_id = $product['vendor_id'];
            $product_sale->sku_id = $sku_id->id;
            $product_sale->sku_no = $product['sku_no'];
            $product_sale->price = $product['skus'][0]['price'];

            $product_sale->quantity = $product['quantity'];

            $product_sale->quantity_tobe_delivered = $product['quantity'];
            $product_sale->total_price = $product_sale->price * $product_sale->quantity;
            $product_sale->save();
        }

        // return $data['all']();
        // $drawers = Drawer::latest()->where('user_id', Auth::id())->first();
        // $products = $data['cart'];

        // $drawer = Drawer::where('user_id', Auth::id())->first();
        // $drawer->sale_total += $total_price;
        // $drawer->expected_closing_amount += $total_price;
        // $drawer->save();
        return $sale;
    }


    public function woocommerce_orders(Request $request)
    {
        // return $request->all();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $vendor_id = $request->vendor_id;
        $woocommerce = new Woocommerce;
        $orders = $woocommerce->orders($start_date, $end_date, $vendor_id);
        $order = new Order;
        $sales = $order->transform_order($orders, $vendor_id);

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

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
    }

    public function woocommerce_webhook(Request $request)
    {
        // $woocommerce = new Client(
        //     'https://nyumbanimart.co.ke',
        //     env('WOOCOMMERCE_CONSUMER_KEY'),
        //     env('WOOCOMMERCE_CONSUMER_SECRET'),
        //     [
        //         'wp_api' => true,
        //         'version' => 'wc/v3'
        //     ]
        // );
        dd('dd');
        // $start_date = $request->start_date;
        // $end_date = $request->end_date;
        $data = [
            'name' => 'Order updated',
            'topic' => 'order.updated',
            'delivery_url' => '/webhook_orders'
        ];
        $vendor_id = $request->vendor_id;

        $woocommerce = new Woocommerce;
        dd($woocommerce->webhook($data));
    }

    public function woocommerce_import(Request $request)
    {
        // return $request->all();
        $user = $this->logged_user();

        $orders = $request->woocommerce_orders;

        foreach ($orders as $value) {
            $products = $value['products'];

            $client = ModelsClient::updateOrCreate(
                [
                    // 'name' => $order['name_of_the_client'],
                    'name' => $value['client_name'],
                    'phone' => $value['phone'],
                    'seller_id' => ($this->logged_user()->is_vendor) ? $this->logged_user()->id  : $request->vendor_id
                ],
                [
                    'address' => $value['address'],
                    // 'email' => $entry['email'],
                    // 'user_id' => 1
                    'user_id' => ($this->logged_user()->is_vendor) ? 1 : $this->logged_user()->id
                ]
            );

            $order = new Sale();
            $order->order_no = $value['order_no'];
            $order->phone = $value['phone'];



            $sale = new Sale();
            $sale->total_price = $value['cod_amount'];
            $sale->sub_total = $value['cod_amount'];
            // $sale->discount = $discount;
            $sale->user_id = ($this->logged_user()->is_vendor) ? 1 : $this->logged_user()->id;
            // $sale->user_id = $this->user->id;
            $sale->ou_id = $user->ou_id;
            $sale->client_id = $client->id;
            // $sale->drawer_id = $drawers->id;
            // $sale->order_no = $request['price'];

            $sale->customer_notes = $value['notes'];
            $sale->order_no = $value['order_no'];
            $sale->warehouse_id = $request->warehouse_id;
            // $sale->ou_id = $ou_id;
            $sale->seller_id = ($this->logged_user()->is_vendor) ? $this->logged_user()->id  : $request->vendor_id;
            // $sale->seller_id = $request['vendor_id'];

            // $product  = Product::find($product['id']);
            // $product = new Product;
            // dd($entry['product_name']);


            // $product = $product->search($entry['product_name']);


            $sale->save();
            // return $product->search($entry['product_name'], $request['vendor_id']);

            foreach ($products as $product_item) {

                // return $product_item;
                // return $product_item['id'];

                // $product = Product::find(332);
                $product = Product::find($product_item['id']);
                // $product = Product::where('product_name', 'LIKE', "%{$product_item['name']}%")->first();
                // Log::debug($product);
                // Log::debug($product_item['name']);
                // return $product;

                $product['quantity'] = $product_item['quantity'];

                // return($product['vendor_id']);
                $sku_id = Sku::where('sku_no', $product['sku_no'])->first('id');
                // return $sku_id;
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product['id'];
                $product_sale->seller_id = $product['vendor_id'];
                $product_sale->sku_id = $sku_id->id;
                $product_sale->sku_no = $product['sku_no'];
                $product_sale->price = $product['skus'][0]['price'];

                $product_sale->quantity = $product['quantity'];

                $product_sale->quantity_tobe_delivered = $product['quantity'];
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->save();
            }



            $sale_arr[] = $sale;

            // return $product->search($order['product_name'], $request->vendor_id);

        }
    }

    public function woocommerce_products(Request $request)
    {
        // return $request->all();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $vendor_id = $request->vendor_id;
        if (!$start_date || !$end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }
        $woocommerce = new Woocommerce;
        $products = $woocommerce->products($start_date, $end_date, $vendor_id);
        $product = new ProductApi;
        return $product->transform_product($products);
    }
    public function import_products(Request $request)
    {
        $user = $this->logged_user();
        ProductUpload::dispatch($request->all(), $user);
        return;
        // return $request->all();
        $products = $request->products;
        $vendor_id = $request->vendor_id;
        $warehouse_id = $request->warehouse_id;
        foreach ($products as $product) {
            $item = new ProductApi;
            $item->create($product, $vendor_id, $warehouse_id, $user);
        }
        return 'success';
    }

    public function webhook_orders(Request $request)
    {
    }
}
