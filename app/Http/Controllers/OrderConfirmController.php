<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderConfirmRequest;
use App\Http\Requests\UpdateOrderConfirmRequest;
use App\Mail\CustomMail;
use App\Models\Sale;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OrderConfirm;
use Illuminate\Support\Facades\Mail;

class OrderConfirmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function thanks()
    {
        return view('thank-you');
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
     * @param  \App\Http\Requests\StoreOrderConfirmRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderConfirmRequest $request)
    {
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderConfirm  $orderConfirm
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $sale = Sale::setEagerLoads([])->with(['client' => function ($q) {
            $q->select('id', 'address');
        }])->select('id', 'order_no', 'client_id')->where('order_no', $order_no)->firstOrFail();

        return view('online-form', compact('sale', 'order_no'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderConfirm  $orderConfirm
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderConfirm $orderConfirm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderConfirmRequest  $request
     * @param  \App\Models\OrderConfirm  $orderConfirm
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderConfirmRequest $request, $order_no)
    {

        try {

            $order = Sale::setEagerLoads([])->select('id', 'ou_id')->where('order_no', $order_no)->first();

            $order_id = $order->id;

            $callback = ($request->callback) ? true : false;

            OrderConfirm::updateOrCreate(
                [
                    'sale_id' => $order_id
                ],
                [
                    'address' => $request->address,
                    'delivery_date' => $request->delivery_date,
                    'notes' => $request->note,
                    'callback' => $callback
                ]
            );
            $callback = ($request->callback) ? 'Yes' : 'No';

            $message = "<b>Order Confirmation</b>\nOrder No. <b>" . $order_no . "</b>\nAddress. <b>" . $request->address . "</b>\n Delivery Date <b>" . $request->delivery_date . "</b>\ncallback. <b>" . $callback . "</b>\nNotes. <b>" . $request->note . "</b>";

            if ($order->ou_id == 1) {
                // $emails = ['itservices@boxleocourier.com', 'operations@boxleocourier.com', 'customerservice@boxleocourier.com', 'support@logixsaas.com'];
                // Mail::to($emails)
                // ->send(new CustomMail($message));

                $chat = TelegraphChat::find(2);
            } elseif ($order->ou_id == 2) {
                $chat = TelegraphChat::where('ou_id', $order->ou_id)->where('id', 6)->first();
            }

            $chat->html($message)->send();

            DB::commit();

            return redirect()->route('thanks');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderConfirm  $orderConfirm
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderConfirm $orderConfirm)
    {
        //
    }
}
