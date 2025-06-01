<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\StockMovement;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\Warehousepickup;
use App\Scopes\BinScope;
use App\Seller;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use DNS2D;

class ProductBinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductBin::setEagerLoads([])->all();
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
        DB::beginTransaction();
        // $invManagement = new SaleInventoryManagement;

        try {
            foreach ($request->products as $key => $product) {
                $bin = Bin::setEagerLoads([])->select('id', 'warehouse_id')->where('id', $product['position'])->first();

                if (!$bin) {
                    abort(422, 'Bin not found!');
                }
                $productBin = ProductBin::where('product_id', $product['id'])->where('bin_id', $bin['id'])->first();

                if ($productBin) {
                    $productBin->warehouseReceive($product['id'], $product['quantity']);
                } else {

                    $bin_model = new ProductBin();
                    $bin_model->createBin($bin->id, $product['id'], $product['quantity'], $bin->warehouse_id);
                }
                $user = Auth::user();

                $data = [
                    'ou_id' => $user->ou_id,
                    'user_id' => $user->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'transaction_type' => 'Receive',
                    'comment' => "Stock level set to {$product['quantity']} by {$user->name}"
                ];
                StockMovement::create($data);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductBin  $productBin
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBin $productBin)
    {
        // ProductBin::where('product_id', )
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductBin  $productBin
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBin $productBin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductBin  $productBin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBin $productBin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductBin  $productBin
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBin $productBin)
    {
        //
    }

    public function pickup_note($id)
    {

        $data = Warehousepickup::find($id);
        // $total = 0;
        // foreach ($data->data as $key => $value) {
        //     $total += $value['price'] * $value['weight'];
        // }

        $vendor = Seller::find($data->vendor_id);
        $data->vendor_name = $vendor->name;
        $company = Company::first();
        // $data->weight = ($data->weight) ?? 0;
        $data->total = 0;
        $currency = Auth::user()->country;
        // $qrcode = DNS2D::getBarcodeHTML($bin->code, 'QRCODE');
        // return view('pdf.pickup.index', compact('data'));
        $data = ['data' => $data, 'company' => $company, 'currency' => $currency];
        $pdf = PDF::loadView('pdf.pickup.index', $data);
        return $pdf->stream('pickup.pdf');
    }

    public function product_sticker($id)
    {
        $data = Warehousepickup::find($id);
        $total = 0;
        foreach ($data->data as $key => $value) {
            $total += $value['price'] * $value['weight'];
        }

        $vendor = Seller::find($data->vendor_id);
        $data->vendor_name = $vendor->name;
        $data->total = $total;

        $qrcode = DNS2D::getBarcodeHTML($data->lot_no, 'QRCODE');
        // return view('pdf.stickers.product', compact('data', 'qrcode'));
        $data = ['data' => $data, 'qrcode' => $qrcode];
        $pdf = PDF::loadView('pdf.stickers.product', $data);
        return $pdf->stream('pickup.pdf');
    }

    public function product_quantity(Request $request)
    {
        // return $request->all();
        return ProductBin::setEagerLoads([])->where('product_id', $request->product_id)->where('bin_id', $request->bin_id)->first('onhand');
    }

    public function stock_update(Request $request)
    {
        if ($request->stock_management == 2) {
            $bins = $request->bins;
            $product_id = $request->id;
            foreach ($bins as $key => $bin) {
                $productBin = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('bin_id', $bin['id'])->first();
                $onhand = $bin['pivot']['onhand'];
                $available_for_sale = $productBin->available_for_sale + $bin['add_qty'];
                $commited = ($bin['pivot']['commited'] == 0) ? $productBin->commited : $bin['pivot']['commited'];
                $delivered = ($bin['pivot']['delivered'] == 0) ? $productBin->delivered : $bin['pivot']['delivered'];
                $old = ['onhand' => $productBin->onhand, 'commited' => $productBin->commited, 'available' => $productBin->available_for_sale];
                $productBin->adjustment($product_id, $onhand, $commited, $available_for_sale, $delivered, $old, $bin['add_qty']);
            }


            // if ($request->update == 'single') {
            //     $pivot = $request->pivot;
            //     $bin_id = Bin::where('code', $request->code)->first('id');
            //     $product = ProductBin::setEagerLoads([])->where('product_id', $pivot['product_id'])->where('bin_id', $bin_id->id)->first();
            //     $product->available_for_sale += $request->add_qty;
            //     $product->onhand = $pivot['onhand'];
            //     // $product->onhand += $pivot['add_qty'];
            //     $product->commited = $pivot['commited'];
            //     $product->quantity += $request->add_qty;
            //     $product->save();
            // } else {
            //     foreach ($bins as $key => $bin) {
            //         $product = ProductBin::setEagerLoads([])->where('product_id', $request->id)->where('bin_id', $bin['id'])->first();
            //         $product->available_for_sale += $bin['add_qty'];
            //         $product->onhand = $bin['pivot']['onhand'];
            //         // $product->onhand += $bin['add_qty'];
            //         $product->commited = $bin['pivot']['commited'];
            //         $product->quantity += $bin['add_qty'];
            //         $product->save();
            //     }
            // }
        } elseif ($request->stock_management == 1) {

            foreach ($request->inventory as  $item) {
                ProductInventory::updateOrCreate(
                    [
                        'warehouse_id' =>  $item['warehouse_id'],
                        'product_id' => $item['product_id'],
                        'seller_id' => $item['seller_id']
                    ],
                    [
                        'onhand' => $item['onhand'] + $item['add_qty'],
                        'available_for_sale' => $item['available_for_sale'] + $item['add_qty'],
                        'commited' => $item['commited'],
                        'delivered' => $item['delivered'],
                        // 'user_id' => $user->id
                    ]
                );
            }
        }
    }

}
