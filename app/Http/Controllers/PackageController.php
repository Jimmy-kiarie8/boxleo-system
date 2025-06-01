<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Package::paginate(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $sale = Sale::find($request->id);
        $package = new Package();
        $data = [
            'sale_id' => $request->id,
            'client_id' => $sale->client_id,
            'quantity' => 1,
            'shipment_date' => now(),
            'status' => 'Pending',
            'user_id' => Auth::id(),
            'package_no' => IdGenerator::generate(['table' => 'packages', 'field' => 'package_no', 'length' => 8, 'prefix' => 'PKG_'])
        ];

        // $data = [
        //     'sale_id' => $request->id,
        //     'client_id' => $sale->client_id,
        //     'quantity' => $request->quantity,
        //     'shipment_date' => $request->shipment_date,
        //     'status' => $request->status,
        //     'user_id' => $request->user_id,
        //     'package_no' => IdGenerator::generate(['table' => 'packages', 'field' => 'package_no', 'length' => 8, 'prefix' => 'PKG_'])
        // ];
        $package->package($data);

        $sale->history_comment = 'Package created';
        $sale->packed = true;
        $sale->user_id = Auth::id();
        $sale->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        //
    }

    public function package_list(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        // return $request->all();
        // $date_arr = ['2023-05-28 00:00:00.000000', '2023-05-30 00:00:00.000000'];
        $date_arr = [Carbon::parse($request->start_date), Carbon::parse($request->end_date)];
        $vendor_id = $request->vendor_id;
        // $orders = Sale::with(['products', 'client'])->whereBetween('delivery_date', [$start_date, $end_date])->where('delivery_status', 'Scheduled')->where('printed', false)->take(100);
        $order =  Sale::where('delivery_status', 'Scheduled')->whereBetween('delivery_date', $date_arr)->where('printed', false)->when(!empty($vendor_id), function ($q) use ($vendor_id) {
            return $q->whereIn('seller_id', $vendor_id);
        });

        $orders = $order->paginate(100);
        // Log::debug(DB::getQueryLog());
        // if (!empty($request->vendor_id)) {
        //     $order = $order->whereIn('seller_id', $request->vendor_id);
        // }
        return $orders;
    }
}
