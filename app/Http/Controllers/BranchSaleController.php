<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Models\BranchSale;
use Illuminate\Http\Request;

class BranchSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Branch::with('sales')->first()->sales()->orderBy('created_at', 'DESC')->paginate();
        // $orders->pivot['products'] = unserialize($orders->pivot['products']);

        $orders->transform(function($order) {
            $order->pivot['products'] = unserialize($order->pivot['products']);
            return $order;
        });
        return $orders;
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
     * @param  \App\Models\BranchSale  $branchSale
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchSale  $branchSale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $products = $request->branch_order['pivot']['products'];
        $branch_sale = BranchSale::where('sale_id', $id)->where('branch_id', $request->branch_order['pivot']['branch_id'])->first();
        $branch_sale->products = $products;

        $branch_sale->receive_status = 'Received';
        $branch_sale->comment = ($request->comment) ? $request->comment : $branch_sale->comment;
        $branch_sale->save();
        return $branch_sale;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchSale  $branchSale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
