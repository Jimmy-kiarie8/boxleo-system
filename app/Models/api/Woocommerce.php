<?php

namespace App\Models\api;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Woocommerce as ModelsWoocommerce;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Woocommerce
{
    public function connection($id)
    {
        // $settings = Setting::where('vendor_id', $id)->first(['woocommerce_url', 'woocommerce_consumer_secret', 'woocommerce_consumer_key']);
        $settings = ModelsWoocommerce::where('vendor_id', $id)->first(['woocommerce_url', 'woocommerce_consumer_secret', 'woocommerce_consumer_key']);
        if (!$settings->woocommerce_consumer_key) {
            abort(422, 'Vendor shop is not connected to the app');
        }
        return $settings;
    }
    public function auth_app($woocommerce)
    {
        $woocommerce = new Client(
           'https://'  .$woocommerce->woocommerce_url,
            $woocommerce->woocommerce_consumer_key,
            $woocommerce->woocommerce_consumer_secret,
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );
        return $woocommerce;
    }
    
    public function orders($woocommerce)
    { $start_date = Carbon::now()->subDays(2)->toIso8601String();
        $end_date = Carbon::now()->toIso8601String();
        
        $orders = $this->auth_app($woocommerce)->get('orders', [
            'after'  => $start_date,
            'before' => $end_date,
            'per_page' => 20
        ]);
        return $orders;
    }
    
    public function products($woocommerce)
    {
        $start_date = Carbon::now()->subDays(2)->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        // dd($start_date, $end_date);
        if (!$start_date || !$end_date) {
            $products = $this->auth_app($woocommerce)->get('products', $parameters = ['per_page' => 100]);
        } else {
            $products = $this->auth_app($woocommerce)->get('products', $parameters = ['after' => $start_date, 'before' => $end_date, 'per_page' => 100]);
        }
        // $product_av = [];
        $product_av = Product::setEagerLoads([])->where('vendor_id', $woocommerce->vendor_id)->get('product_name');

        $pro_arr = [];

        foreach ($product_av as $pro) {
            $pro_arr[] = $pro->product_name;
        }

        $pro_arr;
        // $product_av = Product::where('vendor_id', $id)->get('product_name');
        $product_name = [];
        foreach ($products as $product) {
            if (!in_array($product->name, $pro_arr)) {
                // dd($product->name);
                $product_name[] = $product;
            }
        }
        return $product_name;
    }
    public function webhook($data, $woocommerce)
    {
        return $this->auth_app($woocommerce)->post('webhooks', $data);
    }
    public function webhook_list($woocommerce)
    {
        return $this->auth_app($woocommerce)->get('webhooks');
    }
}
