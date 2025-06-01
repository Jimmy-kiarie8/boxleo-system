<?php

namespace App\Jobs;

use App\Models\DailyStats;
use App\Models\Ou;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stats = new DailyStats();
        // $ous = Ou::all();
        $products = Product::where('active', true)->take(10)->latest()->get();

        foreach ($products as $key => $product) {
            $stats->recordOpeningStats($product->id, 1, 'Opening');
        }

    }
}
