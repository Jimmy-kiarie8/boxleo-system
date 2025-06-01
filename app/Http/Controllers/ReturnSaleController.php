<?php

namespace App\Http\Controllers;

use App\Models\ReturnProductSales;
use App\Models\ReturnSale;
use App\Models\Sale;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ReturnSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $return_sale =  ReturnSale::paginate(200);
        $orders = Sale::select('id')->setEagerLoads([])->with('services')->has('returns')->get();
        // $orders = Sale::select('id')->setEagerLoads([])->with(['returns'])->paginate(4);
        $ids = [];
        foreach ($orders as  $order) {
            $ids[] = $order->id;
        }
        $return_sale =  ReturnSale::with(['product_sales', 'sales'])->whereIn('sale_id', $ids)->paginate(200);
        // $orders_returns =  $orders->returns;
        $return_sale->transform(function ($sales) {
            // dd($sales);

            $sales->sale_order = $sales->sales['order_no'];
            $sales->client_name = $sales->sales['client']['name'];
            // dd($sales);
            return $sales;
        });
        return $return_sale;
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
        $warehouse_id = $request->order['warehouse_id'];
        $sale_id = $request->order['id'];
        $return_order = new ReturnSale();
        $return_order->rma =  IdGenerator::generate(['table' => 'return_sales', 'field' => 'rma', 'length' => 15, 'prefix' => 'RMA_']);
        $return_order->return_date = $request->return_date;
        $return_order->comment = $request->comment;
        $return_order->sale_id = $sale_id;
        // $return_order->warehouse_id = $warehouse_id;
        // $return_order->returned = $request->returned;
        // $return_order->product_sale_id = 39;
        $return_order->save();
        // return $return_order;

        foreach ($request->order['products'] as $product) {
            $warehouse_id = $request->order['warehouse_id'];
            $sale_id = $request->order['id'];
            $return_product = new ReturnProductSales();
            $return_product->return_date = $request->return_date;
            $return_product->comment = $request->comment;
            $return_product->product_sale_id = $product['pivot']['id'];
            $return_product->sale_id = $sale_id;
            $return_product->warehouse_id = $warehouse_id;
            $return_product->tobe_returned = $product['returned'];
            $return_product->return_sale_id = $return_order->id;
            $return_product->save();
        }
    }

    // return $request->all();
    // foreach ($request->order['products'] as $product) {
    //     $warehouse_id = $request->order['warehouse_id'];
    //     $sale_id = $request->order['id'];
    //     $return_order = new ReturnSale();
    //     $return_order->rma =  IdGenerator::generate(['table' => 'return_sales', 'field' => 'rma', 'length' => 15, 'prefix' => 'RMA_']);
    //     $return_order->return_date = $request->return_date;
    //     $return_order->comment = $request->comment;
    //     $return_order->product_sale_id = $product['pivot']['id'];
    //     $return_order->sale_id = $sale_id;
    //     $return_order->warehouse_id = $warehouse_id;
    //     $return_order->tobe_returned = $product['returned'];
    //     $return_order->save();
    // }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReturnSale  $returnSale
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ReturnSale::with(['sales'])->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReturnSale  $returnSale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $return_order = ReturnSale::find($id);
        $return_order->rma = $request->rma;
        $return_order->return_date = $request->return_date;
        $return_order->comment = $request->comment;
        $return_order->sale_id = $request->sale_id;
        $return_order->warehouse_id = $request->warehouse_id;
        $return_order->returned = $request->returned;
        $return_order->save();
        return $return_order;
    }

    public function status_update(Request $request, $id)
    {
        // return $request->all();
        $return_order = ReturnSale::find($id);
        $return_order->status = $request->status;
        $return_order->save();
        return $return_order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReturnSale  $returnSale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function returns_receive(Request $request, $id)
    {
        // return $request->all();
        $products = $request->return_order['sales']['products'];
        $return_order = ReturnSale::with(['product_sales'])->find($id);
        $sale_pro = $return_order->product_sales;
        $warehouse_id = $request->return_order['sales']['warehouse_id'];
        //  dd($sale_pro->id);

        foreach ($products as $product) {
            foreach ($sale_pro as $sale_) {
                // dd($product['id'], $sale_['product_id']);
                if ($product['id'] == $sale_['product_id']) {
                    $return_id = $sale_['pivot']['id'];
                }
            }

            // return $product;
            $returned = $product['returned'];
            $return_product = ReturnProductSales::find($return_id);
            $return_product->receive_status = 'Received';
            $return_product->received_on = $request->received_on;
            $return_product->comment = $request->comment;
            $return_product->returned = $returned;
            $warehouse = new StatusController;
            // dd($request->return_order['sales']['id']);
            $sale = $request->return_order['sales'];
            $sale_order = Sale::find($request->return_order['sales']['id']);
            $old_status = $sale_order->getOriginal('status');
            $warehouse->warehouse_return($sale, $warehouse_id, 'Returned', $old_status);
            $return_product->save();
        }

        $returned = $product['returned'];
        $return_order->receive_status = 'Received';
        $return_order->received_on = $request->received_on;
        $return_order->comment = $request->comment;
        $return_order->save();

        // ($sale, $warehouse_id, $status, $old_status)
        $sale_order->status = 'Closed';
        $sale_order->delivery_status = 'Returned';
        $sale_order->return_count += 1;
        $sale_order->returned_on = now();
        $sale_order->save();
    }

    public function return_item($data, $comment)
    {
        // return $request->all();
        $warehouse_id = $data['warehouse_id'];
        $sale_id = $data['id'];
        $return_order = new ReturnSale();
        $return_order->rma =  IdGenerator::generate(['table' => 'return_sales', 'field' => 'rma', 'length' => 15, 'prefix' => 'RMA_']);
        $return_order->return_date = now();
        $return_order->comment = $comment;
        $return_order->sale_id = $sale_id;
        $return_order->receive_status = 'Complete';
        $return_order->received_on = now();
        $return_order->return_count += 1;
        // $return_order->returned = $request->returned;
        // $return_order->product_sale_id = 39;
        $return_order->save();
        // return $return_order;

        foreach ($data['products'] as $product) {
            // dd($product);
            $warehouse_id = $data['warehouse_id'];
            $sale_id = $data['id'];
            $return_product = new ReturnProductSales();
            $return_product->return_date = now();
            $return_product->comment = $comment;
            $return_product->product_sale_id = $product['pivot']['id'];
            $return_product->sale_id = $sale_id;
            $return_product->warehouse_id = $warehouse_id;
            $return_product->tobe_returned = $product['pivot']['quantity'];
            $return_product->return_sale_id = $return_order->id;
            $return_product->status = 'Received';
            $return_product->receive_status = 'Received';
            $return_product->save();
        }


        // dd($data['products']);
        // $returns = new ReturnSale();

        // $returns->rma =  IdGenerator::generate(['table' => 'return_sales', 'field' => 'rma', 'length' => 15, 'prefix' => 'RMA_']);
        // // $returns->rma = $data->rma;
        // $returns->status = 'Approved';
        // $returns->receive_status = 'Received';
        // $returns->refund_status = 'Complete';
        // // $returns->amount_refunded = 0;
        // // $returns->remaining_refunded = ;
        // $returns->returned = $data->quantity;
        // $returns->comment = $data->comment;

        // $returns->return_date = now();
        // $returns->received_on = now();

        // $returns->warehouse_id = $data->warehouse_id;
        // $returns->sale_id = $data->sale_id;
        // $returns->save();
    }
}
