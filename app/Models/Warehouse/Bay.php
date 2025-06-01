<?php

namespace App\Models\Warehouse;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bay extends Model
{
    use HasFactory;
    protected $fillable = ['code','name', 'row_id'];
    // public $with = ['bins'];

    /**
     * Get all of the bins for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bins(): HasMany
    {
        return $this->hasMany(Bin::class, 'bay_id');
    }

    /**
     * Get the row that owns the Bay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function row(): BelongsTo
    {
        return $this->belongsTo(Row::class, 'row_id');
    }
    
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
}
