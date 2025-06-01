<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSheetUpdateRequest;
use App\Http\Requests\UpdateSheetUpdateRequest;
use App\Models\SheetUpdate;

class SheetUpdateController extends Controller
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
     * @param  \App\Http\Requests\StoreSheetUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSheetUpdateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SheetUpdate  $sheetUpdate
     * @return \Illuminate\Http\Response
     */
    public function show(SheetUpdate $sheetUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SheetUpdate  $sheetUpdate
     * @return \Illuminate\Http\Response
     */
    public function edit(SheetUpdate $sheetUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSheetUpdateRequest  $request
     * @param  \App\Models\SheetUpdate  $sheetUpdate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSheetUpdateRequest $request, SheetUpdate $sheetUpdate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SheetUpdate  $sheetUpdate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SheetUpdate $sheetUpdate)
    {
        //
    }
}
