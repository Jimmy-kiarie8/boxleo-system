<?php

namespace App\Scopes;

use App\Models\SaleZone;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OrderScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::guard('seller')->check() || Auth::guard('api')->check()) {
            $vendor_id = (Auth::guard('seller')->check()) ? Auth::guard('seller')->id() : Auth::guard('api')->id();
            $builder->where('sales.seller_id', $vendor_id);
        } elseif (Auth::guard('web')->check() || Auth::guard('api-callcentre')->check()) {
            if (Auth::user()->hasRole('Call center')) {
                $builder->where('agent_id', Auth::id());
            }
            //  elseif (Auth::user()->hasRole('Agent')) {
            //     $builder->whereIn('id', $this->zone_sales());
            // }
            $builder->where('ou_id', Auth::user()->ou_id);
        }
    }


    public function zone_sales()
    {
        $zones = Zone::where('manager', Auth::id())->pluck('id');
        $sales_ids = SaleZone::whereIn('zone_to', $zones)->pluck('sale_id');
        return $sales_ids;
    }
}
