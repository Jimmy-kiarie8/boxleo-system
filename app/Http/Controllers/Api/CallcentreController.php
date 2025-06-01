<?php

namespace App\Http\Controllers\Api;

use App\Events\UserStatusEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusController;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CallcentreController extends Controller
{
    public function sales($status)
    {
        // return auth('api-callcentre')->user();
        // DB::enableQueryLog(); // Enable query log
        // return Sale::take(3)->get();
        // $sales =  Sale::setEagerLoads([])->with(['client']);
        $sales =  Sale::with(['products', 'client', 'seller']);

        // if (Auth::guard('web')->check()) {
        // $sales = $sales->with(['zones']);
        // }
        $sales = $sales->where('delivery_status', $status);
        $sales = $sales->paginate(100);

        $sale_transform = new Sale;
        $collection = $sale_transform->transform_sales($sales);
        // return $sales;
        return SaleResource::collection($collection);
    }
    public function status_update(Request $request, $id)
    {
        // return $request->all();
        $status = new Status();
        return $status->status_update($request, $id);
    }
    public function status()
    {
        return Status::all();
    }


}
