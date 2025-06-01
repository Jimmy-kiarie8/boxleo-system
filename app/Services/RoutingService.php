<?php

namespace App\Services;

use App\Models\Zone;
use Illuminate\Support\Facades\Log;

class RoutingService
{
    protected $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function getRoute(string $address) // Change to single address
    {
        $zones = Zone::where('ou_id', 1)->get();
        $coordinate = $this->geocodingService->geocodeAddress($address);
        $nearestZone = $this->findNearestZone($coordinate, $zones);

        // return $nearestZone;

        if ($nearestZone) {
            return ['zone' => $nearestZone, 'coordinate' =>$coordinate];
        }
        return null;
    }


    protected function calculateRoute(array $coordinates)
    {
        $route = [];
        $zones = Zone::where('ou_id', 1)->get();

        foreach ($coordinates as $coordinate) {
            $nearestZone = $this->findNearestZone($coordinate, $zones);
            $route[] = $nearestZone->city;
        }

        return $route;
    }

    protected function findNearestZone($coordinate, $zones)
    {
        // Log::debug($coordinate);

        if(empty($coordinate)) {
            return [];
        }
        
        $nearestZone = null;
        $shortestDistance = PHP_INT_MAX;

        foreach ($zones as $zone) {
            $distance = $this->haversine($coordinate['lat'], $coordinate['lng'], $zone->latitude, $zone->longitude);
            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $nearestZone = $zone;
            }
        }
        // Log::debug($shortestDistance);

        if($shortestDistance > 30) {

        }

        return $nearestZone;
    }

    protected function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;  // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;  // Distance in kilometers
    }
}
