<?php

namespace App\Jobs;

use App\Models\api\Woocommerce as ApiWoocommerce;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Shopify;
use App\Models\User;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Models\Woocommerce;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarketPlaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $woocommerces = Woocommerce::where('active', 1)->get();

        foreach ($woocommerces as $key => $woocommerce) {
            $vendor_id = $woocommerce->vendor_id;
            $ou_id = $woocommerce->ou_id;
            $woo_orders = new ApiWoocommerce;
            $woo_orders = $woo_orders->orders($woocommerce);
            $warehouse = Warehouse::find($ou_id);

            $order = new Woocommerce();
            $sales = $order->transform_order($woo_orders, $vendor_id);
            $warehouse_id = $warehouse->id;
            $data = ['orders' => $sales, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
            $user = User::orderBy('id', 'ASC')->first();
            // $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
            ShopifyUpload::dispatch($data, $user, $woocommerce, 'Woocommerce');
        }
    }
}
