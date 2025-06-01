<?php

namespace App\Console\Commands;

use App\Jobs\MpesaJob;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boxleo:mpesa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        MpesaJob::dispatch();
        return Command::SUCCESS;
        $transactions = Transaction::where('code_used', false)->latest()
            ->select('id', 'TransAmount', 'TransID', 'BillRefNumber', 'code_used')
            ->take(100)
            ->where('created_at', '>', '2025-03-18')
            ->latest()
            ->get();


        // $sales = Sale::whereIn('order_no', $transactions)->where('delivery_status', 'In Transit')->get();

        foreach ($transactions as $key => $transaction) {
            $this->order_deliver($transaction->BillRefNumber, $transaction->TransAmount, $transaction->TransID);
        }

        // $order['delivery_status'] = 'Delivered';
        // $status = new Status();
        // $status->status_update(new Request($order), $order['id']);


        return Command::SUCCESS;
    }

    public function order_deliver($order_no, $amount, $mpesa_code)
    {
        // $order = Sale::where('order_no', $order_no)->first();
        $order = Sale::where('delivery_status', '!=', 'Delivered')->whereRaw('LOWER(order_no) = ?', strtolower($order_no))->first();

        if ($order) {
            // $transaction = Transaction::where('TransID', $mpesa_code)->first();
            // $transaction->code_used = true;
            // $transaction->save();
            $status_update = new Status();
            $data = ['delivery_status' => 'Delivered', 'mpesa_code' => $mpesa_code, 'paid' => 1, 'products' => $order->products, 'payment_method' => 'Mpesa', 'warehouse_id' => $order->warehouse_id];

            $total_price = (int) preg_replace("/[^0-9.]/", "", $order->total_price);

            if ($amount < $total_price) {
                // Log::warning($order_no);
                return;
            }

            return $status_update->status_update(new Request($data), $order->id);
        }
        return;
    }
}
