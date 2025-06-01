<?php

namespace App\Services;

use App\Models\Client as ModelsClient;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class YouCanService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // Initialize base URL and API key
        $this->baseUrl = config('youcan.base_url');
        $this->apiKey = config('youcan.api_key');
    }

    public function getProducts()
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->get('/products');
        $this->createProduct($response);

        return json_decode($response->getBody(), true);
    }

    public function getOrders()
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->get('/orders');

        $this->createSale($response);

        return json_decode($response->getBody(), true);
    }

    public function createOrderWebhook($url)
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->post('/webhooks', [
            'json' => [
                'url' => $url,
                'event' => 'order.created',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createSale($orderData)
    {

        DB::beginTransaction();

        try {

            $client = $orderData['shipping']['address'];

            $clientData = [
                'name' => $client['full_name'],
                'phone' => $client['phone'],
                'address' => $client['first_line'],
                'city' => $client['city'],
                'country' => $client['country_name'],
                'postal_code' => $client['zip_code'],
                'email' => $orderData['payment']['address']['email'], // Assuming this is the client's email
                'client_id' => null, // Assuming you assign this later after creating the client in your database
            ];


            ModelsClient::create($clientData);

            $productsData = [];
            foreach ($orderData['payment']['payload']['response']['lineItems'] as $item) {
                $productData = [
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                ];
                $productsData[] = $productData;
            }

            // Product::createOr

            $saleData = [
                'client_id' => $clientData['client_id'],
                'total_price' => $orderData['total'],
                'order_no' => $orderData['ref'],
                'sku_no' => null, // Not provided in the order data
                'customer_notes' => $orderData['notes'],
                'delivery_status' => $orderData['shipping']['status_text'],
            ];
            return compact('clientData', 'saleData', 'productsData');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createProduct($productData)
    {
        $products = [];

        foreach ($productData['variants'] as $variant) {
            $productName = $productData['name'];
            $price = $variant['price'];
            $sku = $variant['sku'];

            $products[] = compact('productName', 'price', 'sku');

            Product::updateOrCreated(
                [
                    "sku_no" => $sku,
                    "vendor_id" => 1
                ],
                [
                    "product_name" => $productName,
                    "price" => $price
                ]
            );
        }



        return $products;
    }
}
