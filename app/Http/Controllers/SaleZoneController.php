<?php

namespace App\Http\Controllers;

use App\Models\SaleZone;
use Illuminate\Http\Request;

class SaleZoneController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleZone  $saleZone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return SaleZone::select('zone_id', 'zone_to', 'id', 'sale_id')->where('sale_id', $id)->latest()->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleZone  $saleZone
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleZone $saleZone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleZone  $saleZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleZone $saleZone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleZone  $saleZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleZone $saleZone)
    {
        //
    }
}
