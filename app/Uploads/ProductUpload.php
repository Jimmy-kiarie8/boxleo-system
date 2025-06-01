<?php

namespace App\Uploads;

use App\Imports\ProductImport;
use App\Models\api\ProductApi;
use App\Models\api\Woocommerce;
use App\Models\AutoGenerate;
use App\Models\Product;
use App\Models\Setting;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProductUpload
{
    public function google($data)
    {

    }

    public function shopify($shopify_data)
    {
        // return $request->all();
        $vendor_id = $shopify_data->vendor_id;
        $settings = Setting::where('vendor_id', $vendor_id)->first();
        if (!$settings->shopify_url) {
            abort(422, 'Vendor shop is not connected to the app');
        }
        $url = 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/products.json';

        $auto = new AutoGenerate;

        try {
            $client = new Client;
            $request = $client->request('GET', $url);
            $response = $request->getBody()->getContents();
            $data_items = json_decode($response);
            $product = new ProductApi;
            return $product->shopify_product($data_items);
        } catch (Exception $e) {
            // Log::debug($e);
            dd($e);
        }
    }

    public function woocommerce($data)
    {
        // return $request->all();
        $start_date = $data->start_date;
        $end_date = $data->end_date;
        $vendor_id = $data->vendor_id;
        if (!$start_date || !$end_date) {
            $start_date = $data->start_date;
            $end_date = $data->end_date;
        }
        $woocommerce = new Woocommerce;
        $products = $woocommerce->products($start_date, $end_date, $vendor_id);
        $product = new ProductApi;
        return $product->transform_product($products);
    }
    public function excel($data)
    {

        // dd( $request->all());
        $products = Excel::toArray(new ProductImport, request()->file('file'));
        $arr = $products[0];
        // dd($arr);
        foreach ($arr as $value) {
            // dd($value);
            $product = new Product();
            $product->product_name = $value['itemname'];
            $product->sku_no = $value['sku'];
            $product->bar_code = $value['barcode'];
            $product->description = $value['itemdescription'];
            $product->price = $value['price'];
            $product->user_id = Auth::id();
            $product->client_id = $request->client;
            $product->save();
        }
    }
}
