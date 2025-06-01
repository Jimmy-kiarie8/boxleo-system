<?php

namespace App\Console\Commands;

use App\Mail\subscription\DeactivationMail;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DeactivationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate unpaid accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // $subscribers = Subscriber::where('at_trial', true)->where('trial_ends', '<', Carbon::now())->where('expired', false);

            // DB::enableQueryLog(); // Enable query log
            $subscribers = Subscriber::where(function ($query) {
                $query->where('at_trial', true)
                    ->where('trial_ends', '<', Carbon::now());
            })->orWhere(function ($query) {
                $query->where('expired', false)
                    ->where('subscription_expire', '<', Carbon::now());
            });
            // dd(DB::getQueryLog()); // Show results of log
            // $subscribers = Subscriber::where('id', 4);
            // dd($subscribers->exists());
            if ($subscribers->exists()) {
                foreach ($subscribers->get() as $subscriber) {
                    $tenant = Crypt::encrypt($subscriber->tenant_id);
                    $url = env('APP_URL') . '/upgrade?domain=' . $tenant;
                    Mail::to($subscriber->email)->send(new DeactivationMail($subscriber, $url));
                }

                $subscribers->update(
                    [
                        'at_trial' => false,
                        'expired' => true
                    ]
                );
            }
            $this->info('Accounts updated');
        } catch (\Exception $e) {
            //throw $th;
            $this->error('Something went wrong!');

            // Log::error($e);
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }
}
