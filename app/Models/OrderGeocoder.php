<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Geocoder\Geocoder;

class OrderGeocoder extends Model
{
    use HasFactory;

    public function geo($address)
    {
        if (!$address) {
            return null;
        }
        // Spatie\Geocoder\Facades\Geocoder::setApiKey()
        $client = new Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $geocoder->setCountry(config('geocoder.country'));
        return  $geocoder->getCoordinatesForAddress($address);
    }

    public function create($data, $id)
    {
        $geo = OrderGeocoder::where('sale_id',$id)->first();
        if ($geo) {
        } else {
            $geo = new OrderGeocoder;
        }
        $geo->accuracy = (array_key_exists('accuracy', $data)) ? $data['accuracy'] : null;
        $geo->formatted_address = (array_key_exists('formatted_address', $data)) ? $data['formatted_address'] : null;
        $geo->lat = (array_key_exists('lat', $data)) ? $data['lat'] : null;
        $geo->lng = (array_key_exists('lng', $data)) ? $data['lng'] : null;
        $geo->place_id = (array_key_exists('place_id', $data)) ? $data['place_id'] : null;
        $geo->country = (array_key_exists('country', $data)) ? $data['country'] : null;
        $geo->city = (array_key_exists('city', $data)) ? $data['city'] : null;
        // $geo->sale_id = 2543;
        $geo->sale_id = $id;
        $geo->save();
        return $geo;
    }


    public function order_geocode($address, $id)
    {
        $geocode  = $this->geo($address);
        // dd($geocode);
        $geocodeArr = [];
        $city = null;
        if ($geocode) {
            if (array_key_exists('address_components', $geocode)) {
                foreach ($geocode['address_components'] as $key => $address_components) {
                    $types = $address_components->types;
                    if ($types) {
                        if ($types[0] == 'administrative_area_level_1') {
                            $city = $address_components->long_name;
                        }
                    }
                }
            }
            $city = str_replace(' County', '',  $city);

            if ($city == 'Uasin Gishu') {
                $city = 'Eldoret';
            }

            $geocodeArr['accuracy'] = $geocode['accuracy'];
            $geocodeArr['formatted_address'] = (array_key_exists('formatted_address', $geocode)) ? $geocode['formatted_address'] : null;
            $geocodeArr['lat'] = (array_key_exists('lat', $geocode)) ? $geocode['lat'] : null;
            $geocodeArr['lng'] = (array_key_exists('lng', $geocode)) ? $geocode['lng'] : null;
            $geocodeArr['country'] = (array_key_exists('country', $geocode)) ? $geocode['country'] : null;
            $geocodeArr['place_id'] = (array_key_exists('place_id', $geocode)) ? $geocode['place_id'] : null;
            $geocodeArr['city'] = $city;
            // $geocodeArr['geo'] = $geocode;
        }
        // dd($city);
        $order_arr['geocode'] = $geocodeArr;

        $geocode = new OrderGeocoder();
        $geocode->create($geocodeArr, $id);
        $geo_zone = null;
        if ($city) {
            $geo_zone = Zone::where('name', 'LIKE', "%{$city}%")->first();
        }
        return $geo_zone;
    }
}
