<?php

namespace App\Models;

use App\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Subscriber extends Model
{
    use HasFactory;
    public $with = ['tenant_plan'];

    protected $casts = [
        'at_trial' => 'boolean',
        'expired' => 'boolean'
    ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id');
    }

    

    /**
     * Get the plan associated with the Subscriber
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tenant_plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function trial($account)
    {
        $subscriber = new Subscriber;

        $subscriber->tenant_id = $account['id'];
        $subscriber->at_trial = true;
        $subscriber->trial_ends = Carbon::now()->addDays(14);
        $subscriber->status = true;
        $subscriber->email = $account['email'];
        $subscriber->name = $account['name'];
        $subscriber->company = $account['company'];
        // $subscriber->company = (array_key_exists('company', $account)) ? $this->account['company'] : '';
        // $subscriber->platform = 'Paypal';
        // $subscriber->plan = 1;
        // $subscriber->plan_id = 1;
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        // $subscriber->subscription_expire = Carbon::today()->addDays(14);
        $subscriber->subscription_adddays = 14;
        $subscriber->expired = false;
        $subscriber->save();
        return $subscriber;
    }

    /*
    public function create($data)
    {

        $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();

        $email = $data['resource']['subscriber']['email_address'];

        $subscriber = Subscriber::where('tenant_id', Auth::id())->first();
        // $subscriber = Subscriber::where('email', $email)->first();
        // $subscriber = Subscriber::where('tenant_id', 'foo')->first();
        $subscriber->at_trial = false;
        $subscriber->status = true;
        $subscriber->email = $email;
        $subscriber->platform = 'Paypal';
        $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->plan_id = 1;
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        $subscriber->subscription_expire = Carbon::today()->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();
    }
*/
    public function setAddonsAttribute($value)
    {
        $this->attributes['addons'] = serialize($value);
    }
    
    public function getAddonsAttribute($value)
    {
        return unserialize($value);
    }

    public function new_subscriber($data)
    {

        // $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();

        $email = $data['resource']['subscriber']['email_address'];

        $subscriber = Subscriber::where('tenant_id', Auth::id())->first();
        // $subscriber = Subscriber::where('email', $email)->first();
        // $subscriber = Subscriber::where('tenant_id', 'foo')->first();
        $subscriber->at_trial = false;
        $subscriber->status = true;
        $subscriber->email = $email;
        $subscriber->platform = 'Paypal';
        $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->plan_id = 1;
        // $subscriber->plan_id = $plan->id;
        $subscriber->subscription_start = now();
        $subscriber->subscription_expire = Carbon::today()->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();
    }

    public function create_sub($id)
    {

        // $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();
        // $email = $data['resource']['subscriber']['email_address'];
        $subscriber = Subscriber::where('tenant_id', Auth::id())->first();

        if ($subscriber) {
            $subscriber->upgrade_plan_id = $id;
        } else {
            $subscriber = new Subscriber;
        }
        // $subscriber = Subscriber::first();
        $subscriber->plan_id = $id;
        $subscriber->tenant_id = Auth::id();
        $subscriber->save();
    }
}
