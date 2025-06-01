<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingDocsRequest;
use App\Http\Requests\UpdateShippingDocsRequest;
use App\Models\ShippingDocs;

class ShippingDocsController extends Controller
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
     * @param  \App\Http\Requests\StoreShippingDocsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShippingDocsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShippingDocs  $shippingDocs
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingDocs $shippingDocs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingDocs  $shippingDocs
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingDocs $shippingDocs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShippingDocsRequest  $request
     * @param  \App\Models\ShippingDocs  $shippingDocs
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShippingDocsRequest $request, ShippingDocs $shippingDocs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingDocs  $shippingDocs
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingDocs $shippingDocs)
    {
        //
    }
}
