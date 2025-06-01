<?php

namespace App\Models\Warehouse;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['code','name', 'warehouse_id'];
    // public $with = ['rows'];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }

    /**
     * Get all of the bins for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bins(): HasMany
    {
        return $this->hasMany(Bin::class, 'area_id');
    }

    /**
     * Get all of the rows for the Area
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rows(): HasMany
    {
        return $this->hasMany(Row::class, 'area_id');
    }

    /**
     * Get the warehouse that owns the Area
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    

}
