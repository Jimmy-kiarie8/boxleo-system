<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrdersJob;
use App\Models\Product;
use App\Models\User;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\api\Order;
use App\Models\Ou;
use App\Models\Warehouse\Warehouse;

class MigrateController extends Controller
{
    public function orders()
    {
        // $job = new ProcessOrdersJob();
        // dispatch($job);
        $url = 'https://boxleocourier.com/dashboard/api/v1/fetch-orders/100?page=61';

        // $url = 'https://boxleocourier.com/dashboard/api/v1/fetch-orders/100';

        $response_1 = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get($url);

        return $ordersData =  json_decode($response_1->getBody()->getContents());
    }

    

   public function extractSku($productName)
    {
        $pattern = '/^([^\-]+)/'; // Regular expression pattern to match the first string before the hyphen

        preg_match($pattern, $productName, $matches);

        if (isset($matches[1])) {
            return $matches[1]; // Return the matched SKU
        }

        return null; // SKU not found
    }
}
