<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'plan',
        'amount',
        'description',
        'features',
        'platform',
        'subscription_id',
        'data',
        'status',
        'orders',
        'portals',
        'users',
        'tracking',
        'warehouses',
        'ou',
        'shopify_integrations',
        'wordpress_integrations',
        'api_integrations',
        'automations',
        'sms',
        'emails',
        'lables',
        'warehouse_management',
        'inventory_management',
        'packing_list'
    ];

    /**
     * Get the subscriber associated with the Plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscriber(): HasOne
    {
        return $this->hasOne(Subscriber::class, 'plan_id');
    }

    public function create_plan($data, $paypal_res)
    {
        // dd($data, $paypal_res->id);
        return Plan::updateOrCreate(
            [
                'plan' => $data['plan']
            ],
            [
                // 'user_id' => 1,
                'description' => $data['subscription_description'],
                // 'features' => $data['features'],
                'amount' => $data['subscription_amount'],
                'plan_id' => $paypal_res->id,
                'subscription_id' => $paypal_res->id,
                'data' => $paypal_res
            ]
        );

    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }

    public function getDataAttribute($value)
    {
        return unserialize($value);
    }

    public function plan_status($id, $status)
    {
        $subscription_ = Plan::where('subscription_id', $id)->first();
        $subscription_->status = $status;
        $subscription_->save();
    }
}
