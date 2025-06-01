<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class AnalyticController extends Controller
{
    public function deliveries()
    {
        return Sale::where('platform', 'Shopify-app')->where('delivery_status', 'Delivered')->count();
    }
    public function returns()
    {
        return Sale::where('platform', 'Shopify-app')->where('delivery_status', 'Returned')->count();
    }
    public function scheduled()
    {
        return Sale::where('platform', 'Shopify-app')->where('delivery_status', 'Scheduled')->count();
    }
    public function dispatched()
    {
        return Sale::where('platform', 'Shopify-app')->where('delivery_status', 'Dispatched')->count();
    }

    public function analytics()
    {
        return array(
            'delivered' => $this->deliveries(),
            'returned' => $this->returns(),
            'scheduled' => $this->scheduled(),
            'dispatched' => $this->dispatched()
        );
    }
}
