<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentDocument extends Model
{
    use HasFactory;

    /**
     * Get the shipments that owns the ShipmentDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipments(): BelongsTo
    {
        return $this->belongsTo(ShipmentDocument::class, 'shipment_id');
    }
}
