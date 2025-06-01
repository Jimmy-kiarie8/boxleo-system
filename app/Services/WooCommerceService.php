<?php

namespace App\Services;

use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Log;

class WooCommerceService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('woocommerce.store_url'),
            config('woocommerce.consumer_key'),
            config('woocommerce.consumer_secret'),
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );
    }

    // Orders methods
    public function getOrders($params = [])
    {
        return $this->client->get('orders', $params);
    }

    public function getOrder($orderId)
    {
        return $this->client->get("orders/{$orderId}");
    }

    public function createOrder($data)
    {
        return $this->client->post('orders', $data);
    }

    public function updateOrder($orderId, $data)
    {
        return $this->client->put("orders/{$orderId}", $data);
    }

    // Products methods
    public function getProducts($params = [])
    {
        return $this->client->get('products', $params);
    }

    public function getProduct($productId)
    {
        return $this->client->get("products/{$productId}");
    }

    public function createProduct($data)
    {
        return $this->client->post('products', $data);
    }

    public function updateProduct($productId, $data)
    {
        return $this->client->put("products/{$productId}", $data);
    }

    // Webhook handler
    public function handleProductWebhook($payload)
    {
        $productId = $payload['id'] ?? null;
        $event = $payload['event'] ?? '';

        if (!$productId) {
            Log::error('Product webhook received without product ID');
            return;
        }

        switch ($event) {
            case 'created':
                $this->handleProductCreated($productId);
                break;
            case 'updated':
                $this->handleProductUpdated($productId);
                break;
            case 'deleted':
                $this->handleProductDeleted($productId);
                break;
            default:
                Log::warning("Unhandled product webhook event: {$event}");
        }
    }

    protected function handleProductCreated($productId)
    {
        $product = $this->getProduct($productId);
        // Implement your logic for handling a new product
        Log::info("New product created: {$productId}");
    }

    protected function handleProductUpdated($productId)
    {
        $product = $this->getProduct($productId);
        // Implement your logic for handling an updated product
        Log::info("Product updated: {$productId}");
    }

    protected function handleProductDeleted($productId)
    {
        // Implement your logic for handling a deleted product
        Log::info("Product deleted: {$productId}");
    }
}