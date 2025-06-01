<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\settings\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;
use Throwable;

class WebhookDispatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The sale instance.
     *
     * @var \App\Models\Sale
     */
    protected $sale;

    /**
     * The event type that triggered the webhook.
     *
     * @var string
     */
    protected $eventType;

    /**
     * The changed fields.
     *
     * @var array
     */
    protected $changedFields;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Sale $sale
     * @param string $eventType
     * @param array $changedFields
     * @return void
     */
    public function __construct(Sale $sale, string $eventType, array $changedFields = [])
    {
        $this->sale = $sale;
        $this->eventType = $eventType;
        $this->changedFields = $changedFields;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Find webhook for the vendor
            $webhook = Webhook::where('vendor_id', $this->sale->seller_id)->first();

            // if (!$webhook) {
            //     Log::info('No webhook found for vendor ID: ' . $this->sale->seller_id);
            //     return;
            // }

            // Load necessary relationships for a complete payload
            $sale = Sale::with([
                'products:id,product_name',
                'client:id,name,email,phone,alt_phone,address,city',
                'seller'
            ])->find($this->sale->id);

            if (!$sale) {
                Log::error('Sale not found with ID: ' . $this->sale->id);
                return;
            }

            // Calculate total price
            $totalPrice = $sale->products->sum(function ($product) {
                return $product->pivot->price * $product->pivot->quantity;
            });

            // Prepare a custom formatted payload
            $customSalePayload = [
                'eventType' => $this->eventType,
                'data' => [
                    'order_no' => $sale->order_no,
                    'total_price' => $totalPrice,
                    'city' => $sale->client->city ?? '',
                    'customer' => [
                        'email' => $sale->client->email ?? '',
                        'full_name' => $sale->client->name ?? '',
                        'mobile' => $sale->client->phone ?? '',
                        'address' => $sale->client->address ?? '',
                    ],
                    'customer_notes' => $sale->customer_notes,
                    'line_items' => $sale->products->map(function ($product) {
                        return [
                            'price' => (float) $product->pivot->price,
                            'quantity' => (int) $product->pivot->quantity,
                            'variant' => [
                                'sku' => $product->pivot->sku_no ?? '',
                                'product_name' => $product->product_name ?? '',
                            ]
                        ];
                    })->toArray(),
                ]
            ];

            Log::info('Webhook payload: ' . json_encode($customSalePayload));
            // return;

            // Prepare headers with auth token if available
            $headers = [];

            if ($webhook->auth_token) {
                $headers['Authorization'] = $webhook->auth_token;
                // $headers['Authorization'] = 'Bearer ' . $webhook->auth_token;
            }

            $secret = $webhook->secret ?? 'ui9-28sjh2-28k7l5';

            try {
                WebhookCall::create()
                    ->url($webhook->url)
                    ->payload($customSalePayload)
                    ->withHeaders($headers)
                    ->useSecret($secret)
                    ->dispatch();

                Log::info('Webhook dispatched successfully to: ' . $webhook->url);
            } catch (Throwable $e) {
                Log::error('Error dispatching webhook: ' . $e->getMessage());
                throw $e;
            }
        } catch (Throwable $e) {
            Log::error('Error dispatching webhook: ' . $e->getMessage());
            throw $e;
        }
    }
}
