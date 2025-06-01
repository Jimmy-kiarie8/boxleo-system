<?php

namespace App\Shopify;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Sku;
use App\Models\Zone;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Shopify
{
    /* public function shopify_transform_1($orders)
    {
        // return $request->all();
        $shop = Auth::user();

        $client = Seller::where('shopify_email', $shop->email)->first('id');
        // DB::enableQueryLog(); // Enable query log

        $exists_orders = Sale::where('seller_id', $client->id)->get('order_no');
        // dd(DB::getQueryLog()); // Show results of log

        //  dd($exists_orders);
        $id = [];
        foreach ($exists_orders as  $exists) {
            $id[] = $exists->order_no;
        }
        // dd($id);



        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order) {
            // dd($order->order_number, $id);
            if (!in_array($order->order_number, $id)) {

                $shipping = $order->shipping_address;
                // $billing = $order->billing_address;
                // dd($shipping);
                $order_arr['created_at'] = $order->created_at;
                $order_arr['order_no'] = $order->order_number;
                $order_arr['address'] = $shipping['address1'];
                $order_arr['client_name'] = $shipping['first_name'] . ' ' . $shipping['last_name'];
                $order_arr['phone'] =  $shipping['phone'];
                $order_arr['email'] = $order->email;
                // $order_arr['quantity'] = $order->number;
                $order_arr['cod_amount'] = $order->current_total_price;
                // $order_arr['status'] = $order->number;
                $order_arr['notes'] = $order->note;
                $products = $order->line_items;

                $product_arr = [];
                $all_products = [];
                $product_check = new Product();
                $order_quantity = 0;
                foreach ($products as $product) {
                    // dd($product);
                    $product_arr['name'] = $product['name'];
                    $product_arr['price'] = $product['price'];
                    $product_arr['quantity'] = $product['quantity'];
                    $order_quantity += $product['quantity'];

                    // $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                    $all_products[] = $product_arr;
                }
                $order_arr['quantity'] = $order_quantity;
                $order_arr['products'] = $all_products;
                $all_orders[] = $order_arr;
            }
        }

        return $all_orders;
    } */

    public function shopify_transform($orders)
    {
        $shop = Auth::user();
        $vendor_id = Seller::where('shopify_email', $shop->email)->first('id');

        if (!$vendor_id) {
            abort(422, 'no vendor account found');
        }
        $vendor_id = $vendor_id->id;

        $exists_orders = Sale::setEagerLoads([])->where('seller_id', $vendor_id)->get('order_no');
        // dd(DB::getQueryLog()); // Show results of log

        //  dd($exists_orders);
        $id = [];
        foreach ($exists_orders as  $exists) {
            $id[] = $exists->order_no;
        }

        // dd($id);
        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order) {
            if (!in_array($order->order_number, $id)) {
                // var_dump($key);
                // var_dump((isset($order->shipping_address)) ? 1: 2);
                $shipping = $order->shipping_address;
                // $billing = $order->billing_address;
                // dd($shipping);
                $order_arr['order_no'] = $order->order_number;
                $order_arr['address'] = $shipping['address1'];
                $order_arr['client_name'] = $shipping['first_name'] . ' ' . $shipping['last_name'];
                $order_arr['phone'] =  $shipping['phone'];
                $order_arr['email'] = $order->email;
                // $order_arr['quantity'] = $order->number;
                $order_arr['cod_amount'] = $order->current_total_price;
                // $order_arr['status'] = $order->number;
                $order_arr['notes'] = $order->note;

                $products = $order->line_items;

                $product_arr = [];
                $all_products = [];
                $product_check = new Product;
                foreach ($products as $product) {
                    // dd($product);
                    $product_arr['name'] = $product['name'];
                    $product_arr['quantity'] = $product['quantity'];

                    $product_arr['id'] = $product_check->check($product['name'], $vendor_id);
                    $all_products[] = $product_arr;
                }
                $order_arr['products'] = $all_products;
                $all_orders[] = $order_arr;
            }
        }

        return $all_orders;
    }

    public function assigned_transform($orders)
    {

        $shop = Auth::user();
        $client = Seller::where('shopify_email', $shop->email)->first('id');

        // DB::enableQueryLog(); // Enable query log
        $orders = Sale::latest()->where('seller_id', $client->id)->get();

        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order) {
            // $shipping = $order->shipping_address;
            // $billing = $order->billing_address;
            // dd($shipping);
            $order_arr['created_at'] = Carbon::parse($order->created_at)->format('D d M Y');
            $order_arr['order_no'] = $order->order_no;
            $order_arr['address'] = $order->client_address;
            $order_arr['client_name'] = $order->client_name;
            $order_arr['phone'] =  $order->client_phone;
            // $order_arr['email'] = $order->email;
            // $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->amount;
            // $order_arr['status'] = $order->number;
            $order_arr['notes'] = $order->notes;
            $order_arr['status'] = $order->status;
            // $products = $order->line_items;

            $product_arr = [];
            $all_products = [];
            $product_check = new Product();
            $order_quantity = 0;
            // dd($order->products);
            foreach ($order->receiveorders as $product) {
                // dd($product);
                $product_arr['name'] = $product['products']['product_name'];
                $product_arr['price'] = $product['products']['price'];
                $product_arr['quantity'] = $product['order_qty'];
                $order_quantity += $product['order_qty'];

                // $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['quantity'] = $order_quantity;
            $order_arr['products'] = $all_products;
            $all_orders[] = $order_arr;
        }

        return $all_orders;
    }

    
    public function upload_function($data, $user, $shop, $market_place)
    {
        $default_zone = Zone::where('ou_id', $shop->ou_id)->where('default_zone', true)->first('id');
        // $prefix = Shopify::where('vendor_id', $data['vendor_id'])->first();
        if (isset($data) && !empty($data)) {
            $orders = $data['orders'];
            // $exists($orders);
            foreach ($orders as $value) {
                $order_no = $shop->order_prefix . $value['order_no'];

                $sale_exist = Sale::where('seller_id', $data['vendor_id'])->where('order_no', $order_no)->exists();
                $products = $value['products'];

                if (!$sale_exist) {
                    $client = Client::updateOrCreate(
                        [
                            'name' => (array_key_exists('client_name', $value)) ?  $value['client_name'] : null,
                            'phone' => (array_key_exists('phone', $value)) ?  $value['phone'] : null,
                            'seller_id' => ($user->is_vendor) ? $user->id  : $data['vendor_id']
                        ],
                        [
                            'address' => (array_key_exists('address', $value)) ?  $value['address'] : null,
                            'email' => (array_key_exists('email', $value)) ?  $value['email'] : null,
                            'user_id' => ($user->is_vendor) ? 1 : $user->id,
                            'ou_id' => $shop->ou_id
                        ]
                    );


                    $sale = new Sale();

                    $cod_amount = str_replace(',', '', $value['cod_amount']);

                    $sale->total_price = (int)$cod_amount;
                    $sale->sub_total = (int)$cod_amount;
                    // $sale->discount = $discount;
                    $sale->user_id = ($user->is_vendor) ? 1 : $user->id;
                    // $sale->user_id = $user->id;
                    $sale->ou_id = $shop->ou_id;
                    $sale->client_id = $client->id;
                    $sale->platform = $market_place;
                    // $sale->drawer_id = $drawers->id;
                    // $sale->order_no = $data['price']'];

                    $status = 'New';
                    $delivery_status = 'New';

                    if (array_key_exists('status', $value)) {
                        if ($value['status']) {
                            $delivery_status = (array_key_exists('status', $value)) ?  $value['status'] : null;

                            if ($value['status'] == 'Scheduled') {
                                $status = 'Confirmed';
                            }
                        }
                    }

                    // if (tenant()->subscriber->tenant_plan->warehouse_management || tenant()->subscriber->tenant_plan->inventory_management) {
                    //     $warehouse = Warehouse::first();
                    //     $warehouse_id = $warehouse->id;
                    // }
                    $sale->waybill_no = (array_key_exists('waybill_no', $value)) ?  $value['waybill_no'] : null;
                    $sale->customer_notes = (array_key_exists('notes', $value)) ? $value['notes'] : null;
                    $sale->order_no = $order_no;
                    $sale->delivery_status = $delivery_status;
                    $sale->status = $status;
                    $sale->delivery_date = (array_key_exists('delivery_date', $value)) ? $value['delivery_date'] : null;
                    $sale->warehouse_id = (array_key_exists('warehouse_id', $data)) ? $data['warehouse_id'] : null;
                    // $sale->ou_id = $ou_id;
                    $sale->seller_id = ($user->is_vendor) ? $user->id  : $data['vendor_id'];
                    // $sale->seller_id = $data['vendor_id']'];

                    // $product  = Product::find($product['id']);
                    // $product = new Product;
                    // dd($entry['product_name']);


                    // $product = $product->search($entry['product_name']);


                    $sale->save();
                    // return $product->search($entry['product_name'], $data['vendor_id']]);


                    $zone = new SaleZone();
                    $zone->create($sale->id, $default_zone->id, $default_zone->id);

                    foreach ($products as $key => $product_item) {

                        // return $product_item;
                        // $product = Product::where('id', $product_item['id'])->first();
                        $product = Product::where('product_name', $product_item['name'])->where('vendor_id', $sale->seller_id)->first();
                        if (!$product) {
                            $product = new Product;
                            $data = ['vendor_id' => $sale->seller_id, 'product_name' => $product_item['name'], 'price' => 0];
                            $product = $product->product_create($data, $user);
                        }


                        // return($product['vendor_id']);
                        $sku_id = Sku::where('sku_no', $product['sku_no'])->first();
                        $quantity = $product_item['quantity'];
                        // Log::debug($sku_id['id']);
                        // return ;
                        // dd($sku_id);
                        $product_sale = new ProductSale();
                        $product_sale->sale_id = $sale->id;
                        $product_sale->product_id = $product['id'];
                        $product_sale->seller_id = $product['vendor_id'];
                        $product_sale->sku_id = $sku_id['id'];
                        $product_sale->sku_no = $product['sku_no'];

                        // Log::debug($sku_id['price']);
                        // return ;
                        $product_sale->price = $sku_id['price'];
                        // $product_sale->price = (array_key_exists(0, $product['skus'])) ? $product['skus'][0]['price'] : 0;

                        $product_sale->quantity = $quantity;

                        $product_sale->quantity_tobe_delivered = $quantity;
                        $product_sale->total_price = (int)$product_sale->price * (int)$product_sale->quantity;
                        $product_sale->save();
                    }

                    $sale_arr[] = $sale;

                    // return $product->search($order['product_name'], $data['vendor_id']);
                }
            }
            return $sale_arr;
        }

    }
}
