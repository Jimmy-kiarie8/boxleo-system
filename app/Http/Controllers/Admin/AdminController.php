<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Plan;
use App\Models\Sale;

class AdminController extends Controller
{
    public function home()
    {
        // abort(403);
        // dd(1);
        $plans = Plan::where('status', true)->get();
        return view('admin.homepage.index', compact('plans'));
        // return view('admin.landing.index', compact('plans'));
    }
    public function pricing()
    {
        // abort(403);
        $plans = Plan::where('status', true)->get();
        return view('admin.landing.pricing', compact('plans'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    public function track($waybill)
    {
        return new SaleResource(Sale::setEagerLoads([])->with(['seller', 'orderHistory' => function($query) {
            $query->setEagerLoads([]);
        }])->where('order_no', $waybill)->firstOrFail());
    }
}
