<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BillingAgreement extends Model
{
    use HasFactory;


    public function billing($logs)
    {
        $email = $logs->payer->payer_info->email;
        $subscriber = Subscriber::where('tenant_id', Auth::id())->first();
        // $subscriber = new Subscriber;
        // $subscriber->new_subscriber($logs);
        // $subscriber = Subscriber::find($id);
        $subscriber->email = $email;
        $subscriber->at_trial = false;
        $subscriber->status = true;
        $subscriber->email = $email;
        $subscriber->platform = 'Paypal';
        $subscriber->agreement_id = $logs->id;
        // $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        $subscriber->subscription_expire = Carbon::today()->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();


        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        // $subscription->billing_agreement_id = $logs->resource->id;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();


        $billing = new BillingAgreement();
        $billing->billing_id = $logs->id;
        $billing->status = $logs->state;
        $billing->description = $logs->description;
        $billing->start_date = $logs->start_date;
        $billing->payer_name = $logs->payer->payer_info->first_name . ' ' . $logs->payer->payer_info->last_name;
        $billing->payer_email = $email;
        $billing->payer_id = $logs->payer->payer_info->payer_id;
        $billing->plan_frequence = $logs->plan->payment_definitions[0]->frequency;
        $billing->plan_amount = $logs->plan->payment_definitions[0]->amount->value;
        $billing->subscriber_id = $subscriber->id;
        $billing->save();
    }
}
