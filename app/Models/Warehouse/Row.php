<?php

namespace App\Models\Warehouse;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Row extends Model
{
    use HasFactory;

    protected $fillable = ['code','name', 'area_id'];
    // public $with = ['bays'];

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
        return $this->hasMany(Bin::class, 'row_id');
    }

    /**
     * Get all of the bays for the Row
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bays(): HasMany
    {
        return $this->hasMany(Bay::class, 'row_id');
    }

    /**
     * Get the area that owns the Row
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
