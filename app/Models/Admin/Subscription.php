<?php

namespace App\Models\Admin;

use App\Models\Subscriber;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;
    /**
     * Get the tenant that owns the Subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function setAddonsAttribute($value)
    {
        $this->attributes['addons'] = serialize($value);
    }
    public function getAddonsAttribute($value)
    {
        return unserialize($value);
    }

    /*
    public function subscribe($data)
    {
        $billing_agreement_id = $data['resource']['id'];
        $subscriber = Subscriber::where('agreement_id', $billing_agreement_id);
        // $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        $subscriber->platform = 'Paypal';
        // $subscriber->plan = $data->plan;
        // $subscriber->plan = $data['resource']['plan_id'];
        $subscriber->agreement_id = $billing_agreement_id;
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();

        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        $subscription->billing_agreement_id = $billing_agreement_id;
        // $subscription->amount = $data['amount']['total'];
        // $subscription->trial_ends = $data->resource;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();
    }*/
}
