<?php

namespace App\Models;

use App\Seller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    public $fillable = ['about', 'address', 'ou', 'email', 'name', 'phone', 'region', 'terms', 'website', 'notes'];

    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'company_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }


    // public function getNameAttribute($value)
    // {
    //     if (Auth::check()) {
    //         $ou = Ou::setEagerLoads([])->find(Auth::user()->ou_id);
    //         return $value . ' ' . $ou->ou;
    //     }
    //     return $value;

    // }
}
