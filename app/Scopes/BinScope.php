<?php

namespace App\Scopes;

use App\Models\Ou;
use App\Models\Warehouse\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BinScope implements Scope
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
        } else if (Auth::check()) {
            $ou_id = Ou::find(Auth::user()->ou_id);
            $warehouse = Warehouse::setEagerLoads([])->where('ou_id', $ou_id->id)->first('id');
            $warehouse_id = $warehouse->id;
            $builder->where('bins.warehouse_id', $warehouse_id);
        }
    }
}
