<?php

namespace App\Http\Controllers;

use App\Models\orderStats;
use App\Http\Requests\StoreorderStatsRequest;
use App\Http\Requests\UpdateorderStatsRequest;

class OrderStatsController extends Controller
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
     * @param  \App\Http\Requests\StoreorderStatsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreorderStatsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\orderStats  $orderStats
     * @return \Illuminate\Http\Response
     */
    public function show(orderStats $orderStats)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\orderStats  $orderStats
     * @return \Illuminate\Http\Response
     */
    public function edit(orderStats $orderStats)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateorderStatsRequest  $request
     * @param  \App\Models\orderStats  $orderStats
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateorderStatsRequest $request, orderStats $orderStats)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\orderStats  $orderStats
     * @return \Illuminate\Http\Response
     */
    public function destroy(orderStats $orderStats)
    {
        //
    }
}
