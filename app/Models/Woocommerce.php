<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Woocommerce extends Model
{
    use HasFactory;

    protected $fillable  = [
        'woocommerce_name', 'woocommerce_url', 'woocommerce_consumer_secret', 'woocommerce_consumer_key', 'active', 'auto_sync', 'order_webhook', 'product_webhook', 'vendor_id', 'sync_interval', 'order_prefix', 'last_order_synced', 'sync_option', 'last_product_synced', 'ou_id'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    public function url($vendor_id, $end_point)
    {
        $settings = Woocommerce::where('vendor_id', $vendor_id)->first();
        if (!$settings) {
            abort(422, 'Vendor shop is not connected to the app');
        }
        $created_at_min = '';
        if ($end_point == 'orders.json') {
            $created_at_min = $settings->last_order_synced;
        } elseif ($end_point == 'products.json') {
            $created_at_min = $settings->last_product_synced;
        }
        return 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/' . $end_point;
        // . '?created_at_min=' . $created_at_min;
    }
    public function connect($data)
    {
        $prefix = substr($data['woocommerce_name'], 0, 3);
        $woocommerce = new Woocommerce();
        $woocommerce->woocommerce_name = $data['woocommerce_name'];
        $woocommerce->woocommerce_url = $data['woocommerce_url'];
        $woocommerce->woocommerce_consumer_secret = $data['woocommerce_consumer_secret'];
        $woocommerce->woocommerce_consumer_key = $data['woocommerce_consumer_key'];
        $woocommerce->active = true;
        $woocommerce->auto_sync = true;
        $woocommerce->order_webhook = true;
        $woocommerce->product_webhook = true;
        $woocommerce->vendor_id = $data['vendor_id'];
        $woocommerce->order_prefix = strtoupper($prefix);
        $woocommerce->ou_id = $data['user_id'];
        $woocommerce->save();
        return $woocommerce;
    }

    public function transform_product($products)
    {

        $product_arr = [];
        $all_products = [];
        // dd($products);

        foreach ($products as $key => $product) {
            // dd($product);
            // $images= $product['pictures'];
            // $image = $images[0]->src;

            // dd($image);
            // return $product->title;
            // if ($key < 3) {
            // dd($product->variants[0]->price);
            $product_arr['product_name'] = $product->name;
            // dd( $product_arr['product_name']);
            $product_arr['price'] = $product->price;

            $product_arr['buying_price'] = $product->price;
            $product_arr['quantity'] = 0;
            $product_arr['onhand'] = 0;
            $product_arr['reorder_point'] = 0;
            $product_arr['image'] = '';
            $product_arr['active'] = 0;
            $all_products[] = $product_arr;
            // }
            // Log::debug($key);
        }
        return $all_products;
    }

    public function transform_order($orders, $vendor_id)
    {
        // dd($orders);

        $order_arr = [];
        $all_orders = [];

        foreach ($orders as $order) {
            $shipping = $order->shipping;
            $billing = $order->billing;

            $name = ($shipping->first_name) ? $shipping->first_name . ' ' . $shipping->last_name : $billing->first_name . ' ' . $billing->last_name;
            $address_1 = ($shipping->address_1) ? $shipping->address_1 : $billing->address_1;
            // dd($shipping->address_1);
            
            $order_arr['order_no'] = $order->number;
            $order_arr['address'] = $address_1;
            $order_arr['client_name'] = $name;
            $order_arr['client_city'] = $shipping->city;
            $order_arr['company'] = $shipping->company;
            $order_arr['country'] = $shipping->country;
            $order_arr['phone'] =  $billing->phone;
            $order_arr['email'] = $billing->email;
            $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->total;
            $order_arr['checkout_id'] = isset($order->checkout_id) ? $order->checkout_id : null;

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

                // $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;


            $all_orders[] = $order_arr;
        }
        return $all_orders;
    }


    public function transform_plugin($orders, $vendor_id)
    {
        // dd($orders);

        $order_arr = [];
        $all_orders = [];

        foreach ($orders as $order) {
            $shipping = $order['shipping'];
            $billing = $order['billing'];
            // dd($shipping->address_1);
            $order_arr['order_no'] = $order['prefix'] . $order['number'];
            $order_arr['address'] = $shipping['address_1'];
            $order_arr['client_name'] = $shipping['first_name'] . ' ' . $shipping['last_name'];
            $order_arr['client_city'] = $shipping['city'];
            $order_arr['company'] = $shipping['company'];
            $order_arr['country'] = $shipping['country'];
            $order_arr['phone'] =  $billing['phone'];
            $order_arr['email'] = $billing['email'];
            $order_arr['quantity'] = $order['number'];
            $order_arr['cod_amount'] = $order['total'];
            // $order_arr['prefix'] = $order['prefix'];
            // $order_arr['status'] = $order['number'];
            $order_arr['notes'] = $order['customer_note'];

            $products = $order['line_items'];

            $product_arr = [];
            $all_products = [];
            $product_check = new Product;
            foreach ($products as $product) {
                // dd($product);
                $product_arr['name'] = $product['name'];
                $product_arr['quantity'] = $product['quantity'];
                $product_arr['price'] = $product['price'];

                // $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;


            $all_orders[] = $order_arr;
        }
        return $all_orders;
    }
}
