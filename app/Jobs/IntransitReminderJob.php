<?php

namespace App\Jobs;

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
use App\Mail\JobErrorMail;

class IntransitReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

            $undispatched = Sale::where('ou_id', $ou->id)->whereDate('dispatched_on', '>', Carbon::now()->subDays(10))->where('delivery_status', 'In Transit')->count();
            $order_no = Sale::setEagerLoads([])->where('ou_id', $ou->id)->whereDate('dispatched_on', '>', Carbon::now()->subDays(10))->where('delivery_status', 'In Transit')->pluck('order_no');
            if ($undispatched > 0) {
                $message = $undispatched . ' orders have been dispatched for more than 10 Days: ' . json_encode($order_no);
                $chat = TelegraphChat::where('ou_id', $ou->id)->first();

                if ($chat) {
                    $chat->markdown($message)->send();
                }
                $subject = 'Un-dispatched Orders';
                Mail::to(['jimlaravel@gmail.com'])->send(new JobErrorMail($message, $subject));
            }
        }

    }
}
