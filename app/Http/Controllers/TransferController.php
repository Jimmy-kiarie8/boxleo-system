<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Scopes\BinScope;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ou_id =  Auth::user()->ou_id;
        $warehouseId = Warehouse::where('ou_id', $ou_id)->first('id')->id;
        return Transfer::with(['product' => function ($q) {
            $q->setEagerLoads([]);
        }, 'warehouse:id,name', 'warehouseFrom:id,name'])->where('from_warehouse_id', $warehouseId)->orWhere('to_warehouse_id', $warehouseId)->paginate();
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
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show($reference)
    {
        return Transfer::with(['product' => function ($q) {
            $q->setEagerLoads([]);
        }, 'warehouse:id,name', 'warehouseFrom:id,name'])->where('reference', $reference)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        //
    }

    public function stock_transfer(Request $request)
    {
        $products = $request->transferItems;
        $warehouse = $request->form['warehouse'];
        $type = $request->form['type'];
        $transfer = new TransferService;

        $ou_id = Auth::user()->ou_id;
        $warehouseId = Warehouse::where('ou_id', $ou_id)->first()->id;


        foreach ($products as $product) {
            $data = ['transfer_type' => $type, 'warehouse' => $warehouse, 'warehouseFrom' => $warehouseId, 'product' => $product];

            // return $data['product']['transferQuantity'];
            $transfer->stock_transfer($data);
        }
        return;

        // return $request->all();
        $product_id = $request->product_id;
        $productId = $request->productId;

        if ($request->transfer_type == 'merchant') {
            $from = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('bin_id', $request->bin_id)->first();
            $to = ProductBin::setEagerLoads([])->where('product_id', $request->productId)->where('bin_id', $request->bin_to)->first();
            $bin_to = Bin::withoutGlobalScope(BinScope::class)->setEagerLoads([])->select('id', 'warehouse_id')->where('id', $request->bin_to)->first('id');

            if ($from) {
                $from->transferReceive($product_id, $request->qty_transfer);
            }
            if (!$to) {
                $bin_model = new ProductBin();
                $bin_model->createBin($bin_to->id, $productId, $request->qty_transfer, $bin_to->warehouse_id);
            } else {
                $to->warehouseReceive($product_id, $request->qty_transfer);
            }
        } else {
            $product = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('bin_id', $request->bin_id)->first();

            $product->transferReceive($product_id, $request->qty_transfer);

            $bin_to = Bin::withoutGlobalScope(BinScope::class)->setEagerLoads([])->select('id', 'warehouse_id')->where('id', $request->bin_to)->first('id');
            $bin_product = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('bin_id', $bin_to->id)->first();
            if (!$bin_product) {
                $bin_model = new ProductBin();
                $bin_model->createBin($bin_to->id, $product_id, $request->qty_transfer, $bin_to->warehouse_id);
            } else {
                $bin_product->warehouseReceive($product_id, $request->qty_transfer);
            }
            return $bin_product;
        }
    }

    public function receive_transfer(Request $request, $reference)
    {
        // $transfer = Transfer::where('reference', $reference)->get();

        // return $request->all();

        $products = $request->products;

        $ou_id = Auth::user()->ou_id;
        $warehouseId = Warehouse::where('ou_id', $ou_id)->first()->id;


        $warehouse = $warehouseId;
        // $type = $request->form['type'];
        $transferService = new TransferService;


        foreach ($products as $product) {
            $data = ['warehouse' => $warehouse, 'to_bin_id' => $product['position'], 'product' => $product];

            // return $data['product']['transferQuantity'];
            $transferService->receive_transfer($product, $data);
        }
        return;
    }
}
