<?php

namespace App\Jobs;

use App\Mail\errors\JobErrorMail;
use App\Subscription\AccountSubscription;
use App\Tenant;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
    
class AccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account)
    {
        $this->account = $account;
        // $this->plan = $plan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Artisan::call('horizon:terminate');
        // $central_domain = 
        // dd(config('tenancy.central_domains'));
        // dd(env('APP_NAME'));
        $tenant = Tenant::create(['id' => $this->account['id']]);
        $account = new AccountSubscription;
        $account->create($this->account, $tenant);

        Artisan::call("tenants:seed", ['--tenants' => $tenant->id]);

        $tenant->domains()->create(['domain' => $tenant->id . env('CENTRAL_DOMAIN')]);

        // Artisan::call('horizon:terminate');
        // dd($tenant->id . env('CENTRAL_DOMAIN'), env('DOMAIN'));

        return ;
    }
    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        // Send user notification of failure, etc...
        $message = 'Account creation failed: ' . $exception->getMessage();
        $subject = 'Account creation failed';
        Mail::to(['support@logixsaas.com'])->send(new JobErrorMail($message, $subject));
    }
}
