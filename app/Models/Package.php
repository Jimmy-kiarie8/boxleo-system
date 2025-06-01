<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['sale_id', 'client_id', 'quantity', 'shipment_date', 'status', 'user_id', 'package_no'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }


    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function package($data)
    {
        Package::create($data);
    }
}
