<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleZone extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'zone_to', 'zone_id', 'dispatch_date'];

    public function create($sale_id, $zone_to, $zone_id)
    {
        try {
            SaleZone::updateOrCreate([
                'sale_id' => $sale_id,
            ], [
                'zone_to' => $zone_to,
                'zone_id' => $zone_id,
                'dispatch_date' => now()
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
