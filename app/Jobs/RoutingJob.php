<?php

namespace App\Jobs;

use App\Models\Ou;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Status;
use App\Rider;
use App\Services\GeocodingService;
use App\Services\RoutingService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoutingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RoutingService $routingService)
    {
        // Get the route for the given address
        // $sales = Sale::where()->get();


        // $ou = Ou::find(1);
        $statuses = ['Awaiting Dispatch'];

        $sales = Sale::setEagerLoads([])->with('client')
            ->select('id', 'client_id', 'order_no', 'ou_id')
            ->where('geocoded', false)
            ->whereIn('delivery_status', $statuses)
            ->whereBetween('delivery_date', [Carbon::today(), Carbon::tomorrow()])
            ->where('ou_id', 1)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->take(50)->latest()->get();
        try {
            foreach ($sales as $key => $sale) {

                DB::beginTransaction();

                try {

                    // Log::warning($sale->order_no);
                    $address = $sale->client->address . ', ' . $sale->city;
                    $route = $routingService->getRoute($address);

                    if ($route) {
                        $coordinate = $route['coordinate'];
                        $zone = $route['zone'];

                        if ($zone->id === 19) {
                            $sale_zones = new SaleZone();
                            $sale_zones->create($sale->id, $zone->id, 1);


                            $rider = Rider::where('zone_id', $zone->id)->first();

                            if ($rider) {
                                $riderSale = new RiderSale();
                                $riderSale->create($sale->id, $rider->id);
                            }


                            Sale::find($sale->id)->update(['longitude' => $coordinate['lng'], 'latitude' => $coordinate['lat'], 'geocoded' => true, 'history_comment' => 'Order geocoded and updated to dispatched!', 'delivery_status' => 'Dispatched', 'dispatched_on' => now()]);
                        }
                    } else {

                        Sale::find($sale->id)->update(['geocoded' => true, 'history_comment' => 'Order Geocorded']);
                    }

                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw $th;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
