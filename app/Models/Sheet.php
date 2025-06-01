<?php

namespace App\Models;

use App\Scopes\SheetScope;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sheet extends Model
{
    use HasFactory;

    public $fillable = ['sheet_name', 'post_spreadsheet_id', 'active', 'auto_sync', 'sync_all', 'sync_interval', 'last_order_synced', 'last_product_synced', 'order_prefix', 'vendor_id', 'ou_id', 'lastUpdatedOrderNumber', 'is_current', 'last_order_upload'];

    /**
     * Get the seller that owns the Sheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'vendor_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    public function getLastOrderUploadAttribute($date)
    {
        if ($date) {

            $timezone = 'Africa/Nairobi';
            return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date)->timezone($timezone))->format('Y-m-d H:i:s');
        }
        return $date;
    }

    // public function getLastOrderSyncedAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    // }

    public static function booted()
    {
        static::addGlobalScope(new SheetScope);
    }
}
