<?php

namespace App\Subscription;

use App\Mail\subscription\WelcomeMail;
use App\Models\Admin\Subscription;
use App\Models\Plan;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccountSubscription
{
    public function create($account, $tenant)
    {

        $plan = ($account['plan']) ? $account['plan'] : 1;
        // Log::debug($account);
        // Log::debug('*********************');
        // Log::debug($tenant);

        $subscriber = new Subscriber();
        $subscriber = $this->trial($account, $plan);

        $subscription = new Subscription();
        // $subscription->company = $account['company'];
        // $subscription->subscription = $account['subscription'];
        // $subscription->domain = 'foo';
        $subscription->subscriber_id = $subscriber->id;
        $subscription->plan_id = $plan;
        $subscription->tenant_id = $tenant->id;
        // $subscription->domain = $tenant->id;
        // $subscription->password = Hash::make($account['password']);
        $subscription->save();

        $name = $account['name'];
        $email = $account['email'];

        // Log::debug($email);

        $url = 'http://' . $account['id'] . env('CENTRAL_DOMAIN');

        $plan_sub = Plan::find($plan);
        $encrypt_domain = Crypt::encrypt($tenant->id);

        Mail::to($email)->send(new WelcomeMail($name, $url, $plan_sub, $encrypt_domain));

        return;
    }

    public function trial($account, $plan)
    {
        $subscriber = new Subscriber;

        $plan_column = Plan::find($plan);

        $subscriber->tenant_id = $account['id'];
        $subscriber->at_trial = ($plan_column->has_trial) ? true : false;
        $subscriber->trial_ends = Carbon::now()->addDay(10);
        $subscriber->status = ($plan_column->has_trial) ? true : false;
        $subscriber->email = $account['email'];
        $subscriber->name = $account['name'];
        $subscriber->company = $account['company'];
        // $subscriber->company = (array_key_exists('company', $account)) ? $this->account['company'] : '';
        // $subscriber->platform = 'Paypal';
        // $subscriber->plan = 1;
        $subscriber->plan_id = $plan;
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        // $subscriber->subscription_expire = Carbon::today()->addDays(14);
        $subscriber->subscription_adddays = 14;
        $subscriber->expired = ($plan_column->has_trial) ? false : true;
        $subscriber->save();
        return $subscriber;
    }

    public function subscribe($data, $plan, $tenant, $addons, $anual_sub)
    {
        $amount = $data['purchase_units'][0]['amount']['value'];
        $billing_agreement_id = $data['id'];
        $subscriber = Subscriber::where('tenant_id', $tenant)->first();
        // $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        // $subscriber->platform = 'Paypal';
        $subscriber->plan = $plan['id'];
        $subscriber->plan_id = $plan['id'];
        // $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->agreement_id = $billing_agreement_id;
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        // if ($subscriber->expired) {
        //     $subscriber->subscription_expire = Carbon::now()->addDays(30);
        // }

        $days = ($anual_sub) ? 365 : 30;

        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays($days);
        $subscriber->subscription_adddays = $days;
        $subscriber->expired = false;
        $subscriber->at_trial = false;
        $subscriber->addons = $addons;
        $subscriber->save();

        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        $subscription->amount = $amount;
        $subscription->billing_agreement_id = $billing_agreement_id;
        $subscription->plan_id = $plan['id'];
        $subscription->tenant_id = $tenant;
        $subscription->addons = $addons;
        // $subscription->amount = $data['amount']['total'];
        // $subscription->trial_ends = $data->resource;
        // $subscription->subscription_start = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscription->subscription_start = Carbon::now();
        $subscription->subscription_expire = Carbon::parse($subscription->subscription_expire)->addDays($days);
        $subscription->subscription_adddays = $days;
        $subscription->expired = false;

        // var_dump($subscription);
        $subscription->save();
    }

    public function renew($data)
    {
        $billing_agreement_id = (array_key_exists('billing_agreement_id', $data['resource'])) ? $data['resource']['billing_agreement_id'] : null;


        // $subscriber = Subscriber::where('agreement_id', $billing_agreement_id)->first();
        $subscriber = Subscriber::where('agreement_id', $billing_agreement_id)->first();
        // $subscriber = Subscriber::where('tenant_id', Auth::id())->first();
        // $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        if ($subscriber) {
            $subscriber->status = true;
            $subscriber->platform = 'Paypal';
            // $subscriber->plan = $data->plan;
            // $subscriber->plan = $data['resource']['plan_id'];
            // $subscriber->agreement_id = $billing_agreement_id;
            // $subscriber->trial_ends = $data->trial_ends;
            $subscriber->subscription_start = $subscriber->subscription_expire;
            $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
            $subscriber->subscription_adddays = 30;
            $subscriber->expired = false;
            $subscriber->save();
        }


        $subscription = new Subscription();
        $subscription->subscriber_id = ($subscriber) ? $subscriber->id : '';
        $subscription->billing_agreement_id = $billing_agreement_id;
        $subscription->amount = $data['resource']['amount']['total'];
        // $subscription->trial_ends = $data->resource;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();
    }

    public function expired()
    {
        $subscriber = Subscriber::find(1);
        $subscriber->status = false;
        $subscriber->expired = true;
        $subscriber->subscription_adddays = 7;
        $subscriber->save();
    }

    public function cancel($data)
    {
    }
}
