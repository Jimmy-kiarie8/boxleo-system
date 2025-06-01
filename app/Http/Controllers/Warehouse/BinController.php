<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;

use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Scopes\BinScope;
use Illuminate\Http\Request;

class BinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bin::with(['warehouse' => function ($q) {
            $q->setEagerLoads([]);
        }, 'area' => function ($q) {
            $q->setEagerLoads([]);
        }, 'row' => function ($q) {
            $q->setEagerLoads([]);
        }, 'bay' => function ($q) {
            $q->setEagerLoads([]);
        }, 'level'])->get();
    }

    public function transfer_bin()
    {
        return Bin::withoutGlobalScope(BinScope::class)->setEagerLoads([])->get();
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


        $code = $request->warehouse_id['code'] . '-' . $request->area_id['code'] . '-' . $request->row_id['code']  . '-' . $request->bay_id['code']  . '-' . $request->level_id['code'] . '-' . $request->position;

        $code = str_replace(' ', '', $code);

        $bin_exists = Bin::where('code', $code)->exists();

        if ($bin_exists) {
            abort(422, 'Bin name already taken');
        }

        $bin = new Bin;
        $bin->name = $request->position;
        $bin->code = $code;
        $bin->warehouse_id =  $request->warehouse_id['id'];
        $bin->area_id =  $request->area_id['id'];
        $bin->row_id =  $request->row_id['id'];
        $bin->bay_id =  $request->bay_id['id'];
        $bin->level_id =  $request->level_id['id'];
        $bin->area_id =  $request->area_id['id'];
        $bin->save();
        return $bin;

        // return Bin::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function show(Bin $bin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function edit(Bin $bin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bin $bin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bin  $bin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bin $bin)
    {
        //
    }

    public function bins_check(Request $request)
    {
        // return $request->all();

        // return ProductBinController::where('product_id', 1)->get();
    }
    public function product_bins($id)
    {
        // return $request->all();

        // return $bins = ProductBin::where('product_id', $id)->get();
        $bins = ProductBin::where('product_id', $id)->get('bin_id', 'available_for_sale');

        $ids = [];
        foreach ($bins as $key => $value) {
            $ids[] = $value['bin_id'];
        }
        // return $id;

        return Bin::setEagerLoads([])->with(['products' => function ($query) use ($id) {
            // $query->setEagerLoads([])->where('product_id', $id);
        }])->whereIn('id', $ids)->get();
    }

    public function warehouse_bin($id)
    {
        return Bin::where('warehouse_id', $id)->get();
    }
}
