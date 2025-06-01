<?php

namespace App\Observers;

use App\Jobs\WebhookDispatchJob;
use App\Models\Client;
use App\Models\Sale;
use App\Models\settings\Webhook;
use Illuminate\Support\Facades\Log;

class ClientObserver
{
    /**
     * Handle the client "updated" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function updated(Client $client)
    {
        try {
            if ($client->isDirty()) {
                $changedFields = array_keys($client->getDirty());
                
                // Only proceed if important fields are changed
                $relevantFields = ['name', 'email', 'phone', 'alt_phone', 'address', 'city'];
                $hasRelevantChanges = count(array_intersect($changedFields, $relevantFields)) > 0;
                
                if ($hasRelevantChanges) {
                    // Get all sales associated with this client to trigger webhooks
                    $sales = Sale::where('client_id', $client->id)->get();
                    
                    foreach ($sales as $sale) {
                        // Check if webhook exists for the vendor
                        $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
                        
                        if ($webhook) {
                            // Dispatch webhook job for client update
                            Log::info('Dispatching client update webhook for sale: ' . $sale->order_no);
                            WebhookDispatchJob::dispatch($sale, 'client_updated', $changedFields);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in ClientObserver: ' . $e->getMessage());
        }
    }
}
