<?php

namespace App\Models\api;

use App\Jobs\OrderUpload;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\User;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Order
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }

    public function shopify_transform_arr($orders, $vendor_id)
    {
        // dd($orders);
        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order_) {
            $order = collect($order_);
            // Log::debug($order);
            // if ($order['fulfillment_status'] != 'fulfilled') {
                // var_dump($key);
                // var_dump((isset($order->shipping_address)) ? 1: 2);
                // dd($order['shipping_address']);
                $shipping = ($order['shipping_address']) ? $order['shipping_address'] : $order['billing_address'];

                if (!$shipping) {
                    continue;                    
                }

                // $billing = $order['billing_address'];
                $order_arr['waybill_no'] = $order['id'];
                $order_arr['order_no'] = $order['order_number'];
                $order_arr['address'] = $shipping->address1;
                $order_arr['client_name'] = $shipping->first_name . ' ' . $shipping->last_name;
                $order_arr['phone'] =  $shipping->phone;
                $order_arr['country'] =  $shipping->country_code;
                $order_arr['currency'] =  $order['currency'];
                $order_arr['currency'] =  $shipping->currency ?? null;
                $order_arr['email'] = $order['email'] ?? null;
                $order_arr['checkout_id'] = $order['checkout_id'] ?? null;

                $order_arr['paid'] = ($order['financial_status'] === 'paid');


                // if (array_key_exists('current_total_price', $order_)) {
                if ($order_->current_total_price) {
                    $cod_amount = $order['current_total_price'];
                } else {
                    $cod_amount = 0;
                }
                $order_arr['cod_amount'] = $cod_amount;
                // $order_arr['status'] = $order['number'];
                $order_arr['notes'] = $order['note'];

                $products = $order['line_items'];

                $product_arr = [];
                $all_products = [];
                $product_check = new Product;
                foreach ($products as $product) {
                    // dd($product);
                    $product_arr['name'] = $product->name;
                    $product_arr['sku'] = $product->sku;
                    $product_arr['quantity'] = $product->quantity;

                    $product_arr['id'] = $product_check->product_compare($product->sku, $product->name, $vendor_id);
                    $all_products[] = $product_arr;
                }
                $order_arr['products'] = $all_products;
                $all_orders[] = $order_arr;
            // }
        }

        return $all_orders;
    }

    public function shopify_transform($orders, $vendor_id)
    {
        // dd($orders);
        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key =>  $order) {
            // dd((object) $order);
            $order = (object) $order;
            // var_dump($key);
            // var_dump((isset($order->shipping_address)) ? 1: 2);
            $shipping = (object) $order->shipping_address;
            $customer = (object) $order->customer;
            // $billing = $order->billing_address;
            // dd($shipping);
            $order_arr['order_no'] = $order->order_number;
            $order_arr['address'] = $shipping->address1;
            $order_arr['client_name'] = $shipping->first_name . ' ' . $shipping->last_name;
            $order_arr['client_city'] = $shipping->city;
            $order_arr['client_country'] = $shipping->country;
            // $order_arr['company'] = $customer->default_address->company;
            $order_arr['currency'] = $customer->currency;
            $order_arr['phone'] =  $shipping->phone;
            $order_arr['email'] = $order->email;
            // $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->current_total_price;
            // $order_arr['status'] = $order->number;
            $order_arr['notes'] = $order->note;
            $products = $order->line_items;

            $product_arr = [];
            $all_products = [];
            $product_check = new Product;
            foreach ($products as  $product) {
                $product = (object)$product;
                // dd($product);
                $product_arr['name'] = $product->name;
                $product_arr['quantity'] = $product->quantity;
                $product_arr['price'] = $product->price;

                // $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;
            $all_orders[] = $order_arr;
        }

        return $all_orders;
    }
    public function excel_transform($orders, $vendor_id)
    {
        // dd($orders);
        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order) {
            $product_arr = [];
            // var_dump($key);
            // var_dump((isset($order->shipping_address)) ? 1: 2);
            // $shipping = $order->shipping_address;
            // $billing = $order->billing_address;
            // dd($shipping);
            $order_arr['order_no'] = $order['order_id'];
            $order_arr['address'] = $order['address'];
            $order_arr['client_name'] = $order['client_name'];
            $order_arr['phone'] =  $order['phone'];
            // $order_arr['email'] = $order['email'];
            // $order_arr['quantity'] = $order['number'];
            $order_arr['cod_amount'] = $order['total_amount'];
            // $order_arr['status'] = $order['number'];
            $order_arr['notes'] = $order['special_instructions'];

            $product_check = new Product;

            // $product_arr[] = $product_check->check($order['product_name'], $vendor_id);
            $order_arr['products'][$key]['id'] = $product_check->check($order['product_name'], $vendor_id);
            $order_arr['products'][$key]['product_name'] = $order['product_name'];
            $order_arr['products'][$key]['quantity'] = $order['quantity'];
            $all_orders[] = $order_arr;
        }

        return $all_orders;
    }

    public function google_transform($orders)
    {
        $orders = collect($orders);

        $orders->transform(function ($order) {
            $order['client_name'] = $order['clientname'];
            $order['delivery_date'] = $order['deliverydate'];
            $order['order_date'] = $order['orderdate'];
            $order['order_no'] = $order['orderid'];
            $order['product_name'] = $order['productname'];
            $order['notes'] = $order['specialinstructions'];
            $order['cod_amount'] = $order['totalamount'];
            return $order;
        });
        return $orders;
    }

    public function transform_order($orders, $vendor_id)
    {
        // dd($orders);

        $order_arr = [];
        $all_orders = [];

        foreach ($orders as $order) {
            $shipping = $order->shipping;
            $billing = $order->billing;
            // dd($shipping->address_1);
            $order_arr['order_no'] = $order->number;
            $order_arr['address'] = $shipping->address_1;
            $order_arr['client_name'] = $shipping->first_name . ' ' . $shipping->last_name;
            $order_arr['phone'] =  $billing->phone;
            $order_arr['email'] = $billing->email;
            $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->total;
            // $order_arr['status'] = $order->number;
            $order_arr['notes'] = $order->customer_note;

            $products = $order->line_items;

            $product_arr = [];
            $all_products = [];
            $product_check = new Product;
            foreach ($products as $product) {
                // dd($product);
                $product_arr['name'] = $product->name;
                $product_arr['quantity'] = $product->quantity;
                $product_arr['price'] = $product->price;

                $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;


            $all_orders[] = $order_arr;
        }
        return $all_orders;
    }
    public function check_product($product_name)
    {
        // dd(similar_text(strtolower('Kusini - Minimal'), strtolower('Six Pack Care ABS Fitness Machine With Pedals Twister'), $percent)/strlen('Six Pack Care ABS Fitness Machine With Pedals Twister') *100);
        $perc = 0;
        $products = Product::setEagerLoads([])->get(['product_name', 'id', 'sku_no']);
        // $products = Product::where('client_id', $client_id)->get();
        $text_per = 0;
        foreach ($products as $product) {
            $length = (strlen($product_name) > strlen($product->product_name)) ? strlen($product_name) : strlen($product->product_name);
            $text_per = similar_text(strtolower($product->product_name), strtolower($product_name), $percent) / $length  * 100;
            // var_dump('*******');
            // var_dump($text_per);

            // var_dump('*******');
            // var_dump($product->product_name);
            // var_dump('¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬¬');
            // var_dump('*******');
            // var_dump($product->product_name);

            // var_dump('*******');

            // var_dump( $perc);
            if ($text_per > $perc) {
                $perc = $text_per;
                $product_id = $product;
            }
        }
        if ($perc > 50) {
            // dd($perc);
            // dd($product_id, $product_name, $perc);
            return $product_id;
        } else {
            // dd($perc);
            $product = new Product();
            $product->id = null;
            $product->sku_no = null;
            return $product->id;
        }
    }

    public function import($data, $user, $vendor)
    {
        OrderUpload::dispatch($data, $user, $vendor);
    }


    
    public function shopify_webhook($order, $vendor_id)
    {
        // var_dump($key);
        // var_dump((isset($order->shipping_address)) ? 1: 2);
        $shipping = $order['shipping_address'];
        // $billing = $order['billing_address'];
        // dd($shipping);
        $order_arr['waybill_no'] = $order['id'];
        $order_arr['order_no'] = $order['order_number'];
        $order_arr['address'] = (array_key_exists('address1', $shipping)) ? $shipping['address1'] : null;
        $order_arr['client_name'] = (array_key_exists('first_name', $shipping) && array_key_exists('last_name', $shipping)) ? $shipping['first_name'] . ' ' . $shipping['last_name'] : null;
        $order_arr['phone'] = (array_key_exists('phone', $shipping)) ? $shipping['phone'] : null;
        $order_arr['email'] = (array_key_exists('email', $shipping)) ? $shipping['email'] : null;
        $order_arr['paid'] = ($order['financial_status'] == 'paid') ? true : false;
        // $order_arr['quantity'] = $order['number'];
        $order_arr['cod_amount'] = (array_key_exists('total_price', $order)) ? $order['total_price'] : 0;
        $order_arr['checkout_id'] = (array_key_exists('checkout_id', $order)) ? $order['checkout_id'] : null;

        // $order_arr['status'] = $order['number'];
        $order_arr['notes'] = $order['note'];
        $order_arr['country'] = (array_key_exists('country_code', $shipping)) ? $shipping['country_code'] : null;

        $products = $order['line_items'];

        $product_arr = [];
        $all_products = [];
        $product_check = new Product;
        foreach ($products as $product) {
            // dd($product);
            $product_arr['name'] = $product['name'];
            $product_arr['quantity'] = $product['quantity'];
            $product_arr['price'] = $product['price'];

            $product_arr['id'] = $product_check->check($product['name'], $vendor_id);
            $all_products[] = $product_arr;
        }
        $order_arr['products'] = $all_products;
        $all_orders[] = $order_arr;
        return $all_orders;
    }
}
