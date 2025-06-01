<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMobileSettingRequest;
use App\Http\Requests\UpdateMobileSettingRequest;
use App\Models\MobileSetting;

class MobileSettingController extends Controller
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
     * @param  \App\Http\Requests\StoreMobileSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMobileSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MobileSetting  $mobileSetting
     * @return \Illuminate\Http\Response
     */
    public function show(MobileSetting $mobileSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MobileSetting  $mobileSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileSetting $mobileSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMobileSettingRequest  $request
     * @param  \App\Models\MobileSetting  $mobileSetting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMobileSettingRequest $request, MobileSetting $mobileSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MobileSetting  $mobileSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileSetting $mobileSetting)
    {
        //
    }
}
