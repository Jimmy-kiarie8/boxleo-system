<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function getImageAttribute($value)
    // {
    //     return  Storage::disk('spaces')->url($value);

    // }
}
