<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plan::all();
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
        $data = $request->all();

        foreach ($request->checkList as $value) {
            if ($value == 'Warehouse management') {
                $data['warehouse_management'] = true;
            } elseif ($value == 'Inventory management') {
                $data['inventory_management'] = true;
            } elseif ($value == 'Packing list') {
                $data['packing_list'] = true;
            }
        }
        $data['status'] = true;
        return Plan::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Plan::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Plan::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function tenantplan($id)
    {
        // return tenant()->subscriber;

        // Plan::find($id);

        $plan = (tenancy()->central(function ($tenant) use($id) {
            return Plan::find($id);
        }));
        // return 1;

        return ['plan' => $plan];
        
        dd(($plan));
    }
}
