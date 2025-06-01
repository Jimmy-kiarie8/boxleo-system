<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStkRequest;
use App\Http\Requests\UpdateStkRequest;
use App\Models\Stk;

class StkController extends Controller
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
     * @param  \App\Http\Requests\StoreStkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStkRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stk  $stk
     * @return \Illuminate\Http\Response
     */
    public function show(Stk $stk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stk  $stk
     * @return \Illuminate\Http\Response
     */
    public function edit(Stk $stk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStkRequest  $request
     * @param  \App\Models\Stk  $stk
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStkRequest $request, Stk $stk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stk  $stk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stk $stk)
    {
        //
    }
}
