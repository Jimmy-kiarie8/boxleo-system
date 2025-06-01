<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse\Product_warehouse;
use Illuminate\Http\Request;
use App\Models\Warehouse\Warehouse;
use App\Models\Sale;
use App\Models\Saleorder;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductWarehouseController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product_warehouse  $product_warehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_warehouse = Product_warehouse::where('product_id', $id)->get();
        $product_warehouse->transform(function ($item) use ($id) {
            $warehouse = $item->warehouses;
            $product = $item->products;
            // dd($item->warehouse_id, $item->seller_id);
            $orders = Sale::where('warehouse_id', $item->warehouse_id)->where('sender_id', $item->seller_id)->where('status', 'Scheduled')->orWhere('status', 'Dispached')->count();
            // $warehouse = Warehouse::find($item->warehouse_id);
            $item->warehouse_name = $warehouse->name;
            // $item->onhand = $warehouse->onhand;
            $item->commited = $orders;
            $item->reorder_point = $product->reorder_point;
            return $item;
        });
        return $product_warehouse;
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product_warehouse  $product_warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $this->Validate($request, [
            'seller_id' => 'required',
            'warehouse' => 'required',
            'qty' => 'required|numeric',
        ]);
        $product_update = Product::find($id);
        $warehouse = Warehouse::find($request->warehouse)->name;
        $product = Product_warehouse::setEagerLoads([])->where('product_id', $id)
            ->where('warehouse_id', $request->warehouse)
            ->where('seller_id', $request->seller_id)
            ->first();
        if ($product) {
            $product->onhand += $request->qty;
            $product->save();
        } else {
            $new_warehouse = new Product_warehouse;
            $new_warehouse->onhand = $request->qty;
            $new_warehouse->product_id = $id;
            $new_warehouse->warehouse_id = $request->warehouse;
            $new_warehouse->seller_id = $request->seller_id;
            $new_warehouse->user_id = Auth::id();
            $new_warehouse->save();
        }
        $user = Auth::user()->name;
        $product_update->instructions = $request->qty . ' ' . $product_update->product_name . ' received in ' . $warehouse . ' by ' . $user;
        $product_update->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product_warehouse  $product_warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function product_details($id)
    {
        return Product::with(['bins' => function ($query) {
            $query->with(['warehouse' => function($q) {
                $q->select('id', 'name')->setEagerLoads([]);
            }, 'area' => function($q) {
                $q->select('id', 'name')->setEagerLoads([]);
            }, 'row' => function($q) {
                $q->select('id', 'name')->setEagerLoads([]);
            }, 'bay' => function($q) {
                $q->select('id', 'name')->setEagerLoads([]);
            }, 'level']);
        }])->find($id);
    }
}
