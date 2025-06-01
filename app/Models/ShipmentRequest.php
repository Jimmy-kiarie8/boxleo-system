<?php

namespace App\Models;

use App\Models\Warehouse\Warehouse;
use App\Seller;
use App\Traits\SaleInventoryManagement;
use App\Traits\ShipmentTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ShipmentRequest extends Model
{
    use HasFactory, ShipmentTrait, SaleInventoryManagement;

    protected $fillable = ['seller_id', 'arrival_date', 'status', 'warehouse_id'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function products()
    {
        return $this->hasMany(ShipmentProduct::class, 'shipment_id');
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
        return $this->hasMany(ShipmentDocument::class, 'shipment_id');
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

    public static function booted()
    {
        if (Auth::guard('seller')->check()) {
            static::addGlobalScope('seller_id', function (Builder $builder) {
                $builder->where('seller_id', Auth::guard('seller')->id());
            });
        }
    }
}
