<?php

namespace App\Services;

use App\Jobs\WebhookDispatchJob;
use App\Models\Sale;
use App\Models\settings\Webhook;
use Illuminate\Support\Facades\Log;

class SaleProductWebhookService
{
    /**
     * Trigger webhook when a sale product (pivot) is updated
     *
     * @param int $saleId The ID of the sale
     * @param array $changedData Data that was changed
     * @return void
     */
    public static function dispatchProductUpdateWebhook(int $saleId, array $changedData = [])
    {
        try {
            // Get the sale
            $sale = Sale::find($saleId);
            
            if (!$sale) {
                Log::error("Cannot dispatch webhook: Sale #{$saleId} not found");
                return;
            }
            
            // Check if webhook exists for the vendor
            $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
            
            if (!$webhook) {
                return;
            }
            
            // Dispatch webhook job for sale product update
            Log::info("Dispatching product update webhook for sale: {$sale->order_no}");
            WebhookDispatchJob::dispatch($sale, 'product_updated', $changedData);
            
        } catch (\Exception $e) {
            Log::error("Error dispatching product update webhook: " . $e->getMessage());
        }
    }
}
