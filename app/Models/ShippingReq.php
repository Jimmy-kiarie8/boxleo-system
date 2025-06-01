<?php

namespace App\Models;

use App\Models\Warehouse\Warehouse;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingReq extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'arrival_date',
        'description',
        'seller_id',
        'warehouse_id',
        'waybill_no',
        'weight',
        'stage',
        'approve_status',
        'status',
        'last_aproved_by',
        'payment_code'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('D d M Y');
    }

    /**
     * Get all of the documents for the ShipmentRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ShippingDocs::class, 'shipping_req_id');
    }

    /**
     * Get the warehouse that owns the ShipmentRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}
