<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\RiderSale;
use App\Rider;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

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
        $chat = TelegraphChat::find(8);

        $riders = Rider::where('ou_id', 1)->where('zone_id', 1)->select('id', 'name', 'zone_id', 'ou_id')->get();
        // $riders = Rider::where('ou_id', 1)->where('zone_id', 1)->select('id', 'name', 'zone_id', 'ou_id')->get();

        foreach ($riders as $rider) {



            $saleIds = RiderSale::where('rider_id', $rider->id)->whereBetween('updated_at', [Carbon::now()->subDays(3), Carbon::now()->subHours(24)])->pluck('sale_id');

            // Log::warning(DB::getQueryLog());

            if (empty($saleIds)) {
                continue;
            }
            $inTransit = Sale::where('ou_id', 1)
                ->whereBetween('dispatched_on', [Carbon::now()->subDays(3), Carbon::now()->subHours(24)])
                ->where('delivery_status', 'In Transit')
                ->whereIn('id', $saleIds)
                ->count();

            if ($inTransit < 1) {
                continue;
            }

            $order_nos = Sale::where('ou_id', 1)
                ->whereBetween('dispatched_on', [Carbon::now()->subDays(3), Carbon::now()->subHours(24)])
                ->where('delivery_status', 'In Transit')
                ->whereIn('id', $saleIds)
                ->pluck('order_no');

            $message = $rider->name . ": \n" . $inTransit . ' orders are still in Transit for more than 24 hours. Order numbers: ' . json_encode($order_nos);



            if ($chat) {
                $chat->markdown($message)->send();
            }
        }
    }
}
