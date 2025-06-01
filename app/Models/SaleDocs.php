<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SaleDocs extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'document'];
    
    /**
     * Get the sale that owns the SaleDocs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // public function getPathAttribute($value)
    // {
    //     if ($value) {
    //         return Storage::url($value); 
    //     }
    //     return $value; 
    // }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
}
