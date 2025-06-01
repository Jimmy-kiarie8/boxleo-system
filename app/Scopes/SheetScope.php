<?php

namespace App\Scopes;

use App\Models\VendorOu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SheetScope implements Scope
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
            $sellers = VendorOu::where('ou_id', Auth::user()->ou_id)->get()->pluck('vendor_id');
            $builder->whereIn('vendor_id', $sellers->toArray());
            // }
        }
    }
}
