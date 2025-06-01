<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreShipmentProductRequest;
use App\Http\Requests\UpdateShipmentProductRequest;
use App\Models\ShipmentProduct;
use App\Models\ShipmentRequest;

class ShipmentProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ShipmentRequest::paginate(100);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreShipmentProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShipmentProductRequest $request)
    {
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShipmentProduct  $shipmentProduct
     * @return \Illuminate\Http\Response
     */
    public function show(ShipmentProduct $shipmentProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShipmentProductRequest  $request
     * @param  \App\Models\ShipmentProduct  $shipmentProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShipmentProductRequest $request, $id)
    {  
        $shipmentProduct = ShipmentProduct::find($id);

        return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShipmentProduct  $shipmentProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShipmentProduct::find($id)->delete();
    }
}
