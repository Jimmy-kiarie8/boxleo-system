<?php

namespace App\Scopes;

use App\Models\VendorOu;
use App\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductScope implements Scope
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

        if (Auth::check()) {
            // if (!Auth::user()->hasRole('Super admin')) {
            $seller_ids = VendorOu::where('ou_id', Auth::user()->ou_id)->pluck('vendor_id');
            $builder->whereIn('vendor_id', $seller_ids->toArray())->where('active', true);
            // }
        } elseif (Auth::guard('seller')->check() || Auth::guard('api')->check()) {
            $vendor_id = (Auth::guard('seller')->check()) ? Auth::guard('seller')->id() : Auth::guard('api')->id();
            $builder->where('vendor_id', $vendor_id)->where('active', true);
        } else {
            // $ou_id = Auth::user()->ou_id;
            $vendor = Seller::setEagerLoads([])->get('id');
            $ids = [];
            foreach ($vendor as $key => $value) {
                $ids[] = $value->id;
            }
            $builder->whereIn('vendor_id', $ids)->where('active', true);
            // Specify the 'vendor_id' column using the 'products' table alias
        }
    }
}
