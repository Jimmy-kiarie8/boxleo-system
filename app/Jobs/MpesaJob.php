<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;

use App\Models\Sale;
use App\Models\Status;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MpesaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 360;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transactions = Transaction::select('id', 'TransAmount', 'TransID', 'BillRefNumber', 'code_used')
            ->where('code_used', false)
            ->where('tried', false)
            ->latest()
            ->take(100)
            ->where('created_at', '>', Carbon::now()->subDays(5))
            ->latest()
            ->get();

        Log::info(count($transactions));


        foreach ($transactions as $key => $transaction) {
            $this->order_deliver($transaction->BillRefNumber, $transaction->TransAmount, $transaction->TransID);
        }
    }

    public function order_deliver($order_no, $amount, $mpesa_code)
    {
        // $order = Sale::where('order_no', $order_no)->first();
        $order = Sale::where('delivery_status', '!=', 'Delivered')->whereRaw('LOWER(order_no) = ?', strtolower($order_no))->first();

        if ($order) {
            Log::info($order->order_no);
            try {

                $status_update = new Status();
                $data = ['delivery_status' => 'Delivered', 'mpesa_code' => $mpesa_code, 'paid' => 1, 'products' => $order->products, 'payment_method' => 'Mpesa', 'warehouse_id' => $order->warehouse_id];

                $total_price = (int) preg_replace("/[^0-9.]/", "", $order->total_price);

                if ($amount < $total_price) {
                    Log::warning('Amount less than total price');
                    return;
                }

                return $status_update->status_update(new Request($data), $order->id);
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            Log::info('Order not found');
            $transaction = Transaction::where('TransID', $mpesa_code)->first();
            $transaction->tried = true;
            $transaction->save();
        }
        return;
    }


    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(10) // The number of jobs to allow
            ->everySeconds(60) // Time span for the rate limit
            ->releaseAfterSeconds(30); // Time to wait if rate limit is exceeded

        return [$rateLimitedMiddleware];
    }
}
