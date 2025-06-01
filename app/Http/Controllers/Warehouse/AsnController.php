<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\Asn;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class AsnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Asn::paginate(200);
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
        $products = $request->products;
        $asn_no =  IdGenerator::generate(['table' => 'asns', 'field' => 'asn_no', 'length' => 10, 'prefix' => 'ASN_']);
        foreach ($products as $key => $product) {
            // $data = [];
            $asn = new Asn;
            $asn->asn_no = $asn_no;
            $asn->vendor_id = $request->vendor_id;
            $asn->product_id = $product['id'];
            $asn->quantity = $product['quantity'];
            $asn->vehicle_no = $request->vehicle_no;
            $asn->po_number = $request->po_number;
            $asn->invoice_no = $request->invoice_no;
            $asn->weight = $request->weight;
            $asn->save();
        }
        return $asn_no;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asn  $asn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Asn::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asn  $asn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $asn = Asn::find($id);

        $data = $request->all();
        $update = new Asn;
        return $update->update_data($asn, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asn  $asn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Asn::find($id)->delete();
    }
}
