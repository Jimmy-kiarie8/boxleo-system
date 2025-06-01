<?php

namespace App\Models;

use App\Seller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Ou extends Model
{
    public $fillable = ['ou', 'currency_code', 'ou_code', 'company_id', 'waybill_terms', 'phone_code', 'ou_phone', 'address', 'phone'];
    public function users()
    {
        return $this->hasMany(User::class, 'ou_id');
    }

    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'vendor_ous', 'vendor_id', 'ou_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'company_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }
    // public function sellers(): BelongsToMany
    // {
    //     return $this->belongsToMany(Seller::class,  'seller_services', 'seller_id', 'service_id')->withPivot('amount');
    // }


    protected static function boot()
    {
        parent::boot();

        static::updated(function () {
            $cacheKey = 'ous';
            Cache::forget($cacheKey);
        });    

        static::created(function () {
            $cacheKey = 'ous';
            Cache::forget($cacheKey);
        });
    }
}
