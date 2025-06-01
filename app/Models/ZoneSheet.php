<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneSheet extends Model
{
    use HasFactory;
    public $fillable = ['sheet_name', 'post_spreadsheet_id', 'active', 'auto_sync', 'sync_all', 'sync_interval', 'last_order_synced', 'last_product_synced', 'order_prefix', 'zones', 'ou_id', 'lastUpdatedOrderNumber', 'is_current'];

    /**
     * Get the zone that owns the ZoneSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function zone(): BelongsTo
    // {
    //     return $this->belongsTo(Zone::class, 'zone_id');
    // }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }
    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = serialize($value);
    }
    public function getZonesAttribute($value)
    {
        if ($value) {
            return unserialize($value);
        }
    }
}
