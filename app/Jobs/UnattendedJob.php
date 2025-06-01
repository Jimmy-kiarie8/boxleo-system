<?php

namespace App\Jobs;

use App\Mail\CustomMail;
use App\Mail\errors\JobErrorMail;
use App\Models\Ou;
use App\Models\Sale;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UnattendedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $ous = Ou::where('active', true)->where('id', '!=', 4)->get();


        // foreach ($ous as $key => $ou) {

            $orders = Sale::where('ou_id', 1)->where('delivery_status', 'New')->count();
            $order_no = Sale::where('ou_id', 1)->where('delivery_status', 'New')->pluck('order_no');
            if ($orders > 0) {
                $message = $orders . ' orders were not attended to. Order numbers: ' . json_encode($order_no);
                // $chat = TelegraphChat::where('ou_id', $ou->id)->first();

                // if ($chat) {
                //     $chat->markdown($message)->send();
                // }
                $subject = 'Unattended orders';
                Mail::to([ 'remittance@boxleocourier.com', 'customerservice@boxleocourier.com', 'cheryl.lukase@boxleocourier.com', 'support@boxleocourier.com',])->send(new JobErrorMail($message, $subject));
            }
        // }

    }
}
