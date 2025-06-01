<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;

use App\Models\Warehouse\Warehousepickup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class WarehousepickupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $lot_no = IdGenerator::generate(['table' => 'warehousepickups', 'field' => 'lot_no', 'length' => 8, 'prefix' => 'Lot']);

        $pickup = new Warehousepickup();
        $pickup->data = $request->products;
        $pickup->arrival_date = Carbon::parse($request->arrival_date);
        $pickup->lot_no = $lot_no;
        $pickup->vendor_id = $request->vendor_id;
        $pickup->user_id = Auth::id();
        $pickup->save();
        return $pickup;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehousepickup  $warehousepickup
     * @return \Illuminate\Http\Response
     */
    public function show(Warehousepickup $warehousepickup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehousepickup  $warehousepickup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehousepickup $warehousepickup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehousepickup  $warehousepickup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehousepickup $warehousepickup)
    {
        //
    }
}
