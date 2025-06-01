<?php

namespace App\Models\Admin;

use App\Mail\subscription\WelcomeMail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;

class Account
{
    public function create($account, $tenant)
    {
        // Log::debug($account);
        // Log::debug('*********************');
        // Log::debug($tenant);

        $subscriber = new Subscriber();
        $subscriber->trial($account);

        $subscription = new Subscription();
        // $subscription->company = $account['company'];
        // $subscription->subscription = $account['subscription'];
        // $subscription->domain = 'foo';
        $subscription->subscriber_id = $subscriber->id;
        $subscription->plan_id = 1;
        $subscription->tenant_id = $tenant->id;
        // $subscription->domain = $tenant->id;
        // $subscription->password = Hash::make($account['password']);
        $subscription->save();

        $name = $account['name'];
        $email = $account['email'];

        // Log::debug($email);

        $url = 'http://' . $account['id'] . env('CENTRAL_DOMAIN');

        Mail::to($email)->send(new WelcomeMail($name, $url, $account['password'], $plan));

        return ;

    }
}


