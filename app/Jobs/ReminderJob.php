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

class ReminderJob implements ShouldQueue
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
        $ous = Ou::where('active', true)->where('id', '!=', 4)->get();


        foreach ($ous as $key => $ou) {

            $undispatched = Sale::where('ou_id', $ou->id)->whereBetween('delivery_date', [Carbon::now()->subWeek(), Carbon::today()])->whereIn('delivery_status', ['Awaiting Dispatch', 'Scheduled', 'Rescheduled'])->whereDate('created_at', '<', Carbon::today())->count();
            $order_no = Sale::where('ou_id', $ou->id)->whereBetween('delivery_date', [Carbon::now()->subWeek(), Carbon::today()])->whereIn('delivery_status', ['Awaiting Dispatch', 'Scheduled'])->whereDate('created_at', '<', Carbon::today())->pluck('order_no');
            if ($undispatched > 0) {
                $message = $undispatched . ' orders were not dispatched. Order numbers: ' . json_encode($order_no);
                $chat = TelegraphChat::where('ou_id', $ou->id)->first();

                if ($chat) {
                    $chat->markdown($message)->send();
                }
                $subject = 'Un-dispatched Orders';
                Mail::to(['jimlaravel@gmail.com'])->send(new JobErrorMail($message, $subject));
            }
        }


        $wednesday = Carbon::now()->isWednesday();
        if ($wednesday) {
            # code...
            $inTransit = Sale::where('ou_id', 1)->whereBetween('dispatched_on', [Carbon::now()->subWeeks(2), Carbon::today()->subWeek()])->where('delivery_status', 'In Transit')->count();

            $order_no = Sale::where('ou_id', 1)->whereBetween('dispatched_on', [Carbon::now()->subWeeks(2), Carbon::today()->subWeek()])->where('delivery_status', 'In Transit')->pluck('order_no');
            if ($inTransit > 0) {
                $message = $inTransit . ' orders are 2 weeks in the field. Order numbers: ' . json_encode($order_no);
                $emails = ['jimlaravel@gmail.com', 'operations@boxleocourier.com', 'support@boxleocourier.com', 'remittance@boxleocourier.com', 'customersupport@boxleocourier.com'];
                Mail::to($emails)
                    ->send(new CustomMail($message));
            }
        }
    }
}
