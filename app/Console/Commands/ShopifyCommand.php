<?php

namespace App\Console\Commands;

use App\Http\Controllers\Marketplace\ShopifyappController;
use App\Http\Controllers\ShopifyController;
use App\Models\Shopify;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders from shopify';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shops = Shopify::all();

        if (count($shops) > 0) {
            foreach ($shops as $shop) {
                if ($shop->sync_option == 'Auto') {
                    $last_sync = $shop->last_order_synced;
                    $frequency = ($shop->sync_interval == 30) ? Carbon::parse($last_sync)->addMinutes($shop->sync_interval) : Carbon::parse($last_sync)->addHours($shop->sync_interval);
                    if ($frequency <= Carbon::now()) {
                        $sync = new ShopifyappController;
                        $sync->sync_orders(new Request($shop->toArray()));
                    }
                }
            }
        }
        $this->info('Orders sychronized');
    }
}
