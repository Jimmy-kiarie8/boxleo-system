<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentProduct extends Model
{
    use HasFactory;

    protected $fillable = ['shipment_id', 'product_id', 'quantity', 'received_quantity'];

    protected $appends = ['remaining'];

    public function request()
    {
        return $this->belongsTo(ShipmentRequest::class);
    }

    public function getRemainingAttribute()
    {
        return $this->attributes['quantity'] - $this->attributes['received_quantity'];

    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('D d M Y');
    }
}
