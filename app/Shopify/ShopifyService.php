<?php

namespace App\Shopify;

use App\Models\api\ProductApi;
use App\Models\Sale;
use App\Models\Shopify;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    // protected $baseUrl;
    // protected $adminKey;
    // protected $apiPassword;
    // protected $accessToken;

    public function __construct()
    {
        // Initialize Shopify API credentials and base URL
    }

    public function getProducts($vendor_id, $id)
    {
        $shop = Shopify::find($id);
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

    public function createOrder($data)
    {
    }

    public function closeOrder($orderId, $baseUrl, $adminKey)
    {
        $orderId = 4255485100110;
        $url = $baseUrl . '/admin/api/2023-07/orders/' . $orderId . '/close.json';
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $adminKey,
            'Content-Type' => 'application/json',
        ])
            ->post($url, [
                // Request body here (if needed)
                // Example: 'key' => 'value',
            ]);

        // You can access the response status code and content as needed
        $responseStatusCode = $response->status();
        $responseContent = $response->json();
        dd($responseContent);
    }

    public function updateOrder($id, $notes)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id', 'order_no')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $baseUrl = 'https://' .  $shopify->shopify_url;
        $adminKey = $shopify->shopify_secret;

        // $baseUrl = $url . '/admin/api/2023-07/orders/' . $orderId . '/fulfillment_orders.json';

        $url = $baseUrl . '/admin/api/2023-07/orders/' . $orderId . '.json';
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $adminKey,
            'Content-Type' => 'application/json',
        ])
            ->put($url, ['order' => [
                'id' => $orderId,
                'note' => $notes
            ],]);

        // You can access the response status code and content as needed
        $responseStatusCode = $response->status();
        $responseContent = $response->json();
        return ($responseContent);
    }
    public function paymentOrder($orderId, $baseUrl, $adminKey)
    {
        $orderId = 4255485100110;
        $url = $baseUrl . '/admin/api/2023-07/orders/' . $orderId . '/transactions.json';
        $transactionData = [
            'transaction' => [
                'currency' => 'KES',
                'amount' => '10.00',
                'kind' => 'capture',
                'parent_id' => 389404469,
            ],
        ];
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => config('shopify.access_token'),
            'Content-Type' => 'application/json',
        ])
            ->post($url, $transactionData);

        // You can access the response status code and content as needed
        $responseStatusCode = $response->status();
        $responseContent = $response->json();
        dd($responseContent);
    }

    public function updateFulfillment($id)
    {
        $order = Sale::setEagerLoads([])->select('id', 'waybill_no', 'seller_id', 'order_no')->find($id);
        $orderId = $order->waybill_no;
        $vendor_id = $order->seller_id;
        $shopify = Shopify::where('vendor_id', $vendor_id)->first();
        $baseUrl = 'https://' .  $shopify->shopify_url;
        $adminKey = $shopify->shopify_secret;

        $url = $baseUrl . '/admin/api/2023-07/orders/' . $orderId . '/fulfillment_orders.json';

        // Fetch
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Shopify-Access-Token' => $adminKey
        ])->get($url);

        $responseData = $response->json();
        if(!array_key_exists('fulfillment_orders', $responseData)) {
            return false;
        }
        $fulfillment_id = $responseData['fulfillment_orders'][0]['id'];

        // return $fulfillment_id;

        // Fulfill an order
        $response = $this->fulfill($fulfillment_id, $baseUrl, $adminKey, $order->order_no);
        return $response;

    }
    public function fulfill($fulfillment_id, $baseUrl, $adminKey, $order_on)
    {
        $url = $baseUrl . '/admin/api/2023-07/fulfillments.json';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Shopify-Access-Token' => $adminKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
                "fulfillment" => [
                    "line_items_by_fulfillment_order" => [
                        [
                            "fulfillment_order_id" => $fulfillment_id,
                            // "fulfillment_order_line_items" => []
                        ]
                    ],
                    "tracking_info" => [
                        "number" => $order_on,
                        "url" => "https://app.boxleocourier.com/track?order_no=" . $order_on,
                        "company" => env('APP_NAME')
                    ],
                    "notify_customer" => false,
                    "origin_address" => null,
                    "message" => "Order Delivered"
                ]
            ]);

        return $response->json();
    }
}
