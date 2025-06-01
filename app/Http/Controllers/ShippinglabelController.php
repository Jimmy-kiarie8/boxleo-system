<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shippinglabel;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ShippinglabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Shippinglabel::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $label_id = IdGenerator::generate(['table' => 'shippinglabels', 'field' => 'label_id', 'length' => 14, 'prefix' => 'LBL_']);
        // $order = Sale::find($request->id);
        
        return Shippinglabel::firstOrCreate(
            [
                'sale_id' => $request->id
            ],
            [
                'label_id' => $label_id
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shippinglabel  $shippinglabel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Shippinglabel::with(['sales' => function($query) {
            $query->setEagerLoads([''])->with(['seller', 'client']);
        }])->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shippinglabel  $shippinglabel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shippinglabel  $shippinglabel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
