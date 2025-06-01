<?php

namespace App\Jobs;

use App\Models\Ou;
use App\Models\Sale;
use App\Models\Zone;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function geocodeAddress(string $address)
    {
        // Log::debug($address);

        try {
            $httpClient = new Client();

            // $apiKey = config('services.geocoding.api_key');
            $apiKey = env('GEOCODING_API_KEY');
            // $apiKey = config('services.geocoding.api_key');
            $response = $httpClient->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => $address,
                    'key' => $apiKey,
                    'region' => 'ke',
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'OK') {
                return $data['results'][0]['geometry']['location'];
            }
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }

        // throw new \Exception('Geocoding failed.');
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $zones = Zone::whereNull('longitude')->whereNull('latitude')->where('ou_id', 1)->get();

            foreach ($zones as $key => $zone) {
                $cood = $this->geocodeAddress($zone->name);

                if ($cood) {
                    $zone->latitude = $cood['lat'];
                    $zone->longitude = $cood['lng'];
                    $zone->save();
                } else {
                    Log::warning("***************Not Found**************");
                    Log::alert($zone->name);
                    Log::alert($cood);
                    Log::warning("***************Not Found**************");
                }

                // 'latitude', 'longitude'
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return;


        $ou = Ou::find(1);
        // $ous = Ou::where('active', true)->get();

        $statuses = ['Dispatched', 'In Transit'];
        // $statuses = ['Delivered', 'Returned', 'In Transit'];

        // foreach ($ous as $ou) {
        $sales = Sale::setEagerLoads([])->with('client')
            ->select('id', 'client_id', 'order_no', 'ou_id')
            ->where('geocoded', false)
            ->whereIn('delivery_status', $statuses)
            ->where('ou_id', $ou->id)
            ->where('created_at', '<', Carbon::today()->subMonth())
            ->take(100)->latest()->get();
        try {
            foreach ($sales as $key => $sale) {
                // Log::warning($sale->order_no);
                $address = $sale->client->address . ', ' . $sale->city;
                $start_lat = -1.3472057036384597;
                $start_lng = 36.901001510044445;


                $coord_url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . $address . '.json';
                $response = Http::get($coord_url, [
                    'access_token' => env('MAPBOX_TOKEN'),
                    'country' => $ou->ou_code
                ]);

                // You can then access the response body and status code
                $responseData = $response->json();

                if (!empty($responseData['features'])) {

                    $center = $responseData['features'][0]['center'];

                    $lng = $center[0];
                    $lat = $center[1];

                    $distance_url = 'https://api.mapbox.com/directions/v5/mapbox/driving/' . $start_lng . ',' . $start_lat . ';' . $lng . ',' . $lat . '?steps=false&geometries=geojson';

                    $response = Http::get($distance_url, [
                        'access_token' => env('MAPBOX_TOKEN'),
                    ]);

                    $responseData = $response->json();

                    $distance = $responseData['routes'][0]['distance'] / 1000;
                    $duration = $responseData['routes'][0]['duration'] / 3600;

                    Sale::find($sale->id)->update(['longitude' => $lng, 'latitude' => $lat, 'distance' => $distance, 'geocoded' => true]);
                } else {
                    Sale::find($sale->id)->update(['geocoded' => true]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        // }

    }
}
