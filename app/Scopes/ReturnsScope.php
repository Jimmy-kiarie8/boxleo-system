<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ReturnsScope implements Scope
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
            $builder->where('seller_id', $vendor_id);
        }
    }
}
