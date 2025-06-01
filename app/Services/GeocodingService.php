<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeocodingService
{
    protected $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function geocodeAddress(string $address)
    {

        // $apiKey = config('services.geocoding.api_key');
        $apiKey = env('GEOCODING_API_KEY');
        // $apiKey = config('services.geocoding.api_key');
        $response = $this->httpClient->get('https://maps.googleapis.com/maps/api/geocode/json', [
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

        return [];
        // throw new \Exception('Geocoding failed.');
    }
}
