<?php

namespace App\Observers;

use App\Models\ReceiveHistory;
use App\Models\ReceiveOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReceiveObserver
{
    /**
     * Handle the saleorder "created" event.
     *
     * @param  \App\model\ReceiveOrder  $receiveOrder
     * @return void
     */
    public function created(ReceiveOrder $receiveOrder)
    {
        // $reference_no = new AutoGenerate;
        $history  = new ReceiveHistory();
        if (Auth::guard('clients')->check()) {
            $user  = Auth::guard('clients')->user();
            // dd($user->id);
        } elseif (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } else {
            // $user = Auth::user();
            $user = User::find($receiveOrder->user_id);
        }

        // dd($receiveOrder->user_id);
        $history->receive_id = $receiveOrder->id;
        $history->user_id = $user->id;
        $history->action = 'Order Created by ' . $user->name;
        $history->save();
    }

    /**
     * Handle the saleorder "updated" event.
     *
     * @param  \App\model\ReceiveOrder  $receiveOrder
     * @return void
     */
    public function updated(ReceiveOrder $receiveOrder)
    {
        // $reference_no = new AutoGenerate;
        $history  = new ReceiveHistory();
        if (Auth::guard('clients')->check()) {
            $user  = Auth::guard('clients')->user();
            // dd($user->id);
        } elseif (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } else {
            // $user = Auth::user();
            $user = User::find($receiveOrder->user_id);
        }

        // dd($receiveOrder->user_id);
        $history->receive_id = $receiveOrder->id;
        $history->user_id = $user->id;
        $history->action = 'Order updated by ' . $user->name;
        $history->save();
    }

    /**
     * Handle the saleorder "deleted" event.
     *
     * @param  \App\model\ReceiveOrder  $receiveOrder
     * @return void
     */
    public function deleted(ReceiveOrder $receiveOrder)
    {
        //
    }

    /**
     * Handle the saleorder "restored" event.
     *
     * @param  \App\model\ReceiveOrder  $receiveOrder
     * @return void
     */
    public function restored(ReceiveOrder $receiveOrder)
    {
        //
    }

    /**
     * Handle the saleorder "force deleted" event.
     *
     * @param  \App\model\ReceiveOrder  $receiveOrder
     * @return void
     */
    public function forceDeleted(ReceiveOrder $receiveOrder)
    {
        //
    }
}
