<?php

namespace App\Jobs;

use App\Models\Woocommerce;
use App\Models\User;
use App\Models\api\Woocommerce as ApiWoocommerce;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WooOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    /**
     * Execute the job.
     */
    public function handle()
    {
        $woocommerces = Woocommerce::all();

        foreach ($woocommerces as $woocommerce) {
            $this->syncOrders($woocommerce);
        }
    }

    /**
     * Sync orders for a specific Woocommerce instance.
     *
     * @param Woocommerce $woocommerce
     */
    private function syncOrders(Woocommerce $woocommerce)
    {
        $vendorId = $woocommerce->vendor_id;
        $apiWoocommerce = new ApiWoocommerce;
        $wooOrders = $apiWoocommerce->orders('', '', $vendorId);

        $sales = $woocommerce->transform_order($wooOrders, $vendorId);

        $data = [
            'orders' => $sales, 
            'vendor_id' => $vendorId, 
            'warehouse_id' => 1
        ];

        $user = User::oldest('id')->first();

        ShopifyUpload::dispatch($data, $user, $woocommerce, 'Woocommerce');
    }
}
