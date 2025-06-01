<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderSale extends Model
{
    public $fillable = ['rider_id', 'sale_id', 'delivery_status', 'receive_status', 'sent', 'received', 'comment', 'products', 'dispatch_date'];
    public function getProductsAttribute($value)
    {
        return unserialize($value);
    }
    public function setProductsAttribute($value)
    {
        $this->attributes['products'] = serialize($value);
    }

    public function create($sale_id, $rider_id)
    {
        RiderSale::updateOrCreate([
            'sale_id' => $sale_id,
        ], [
            'rider_id' => $rider_id,
            'delivery_status' => 'Pedding',
            'receive_status' => 'Pedding',
            'dispatch_date' => now()
        ]);
    }

    public function undispatch($id)
    {
        $rider = RiderSale::where('sale_id', $id)->first();
        if ($rider) {
            $rider->delete();
        }

        $zone = SaleZone::where('sale_id', $id)->first();
        if ($zone) {
            $zone->delete();
        }
    }
}
