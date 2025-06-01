<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceive extends Model
{
    use HasFactory;

    protected $fillable = ['reference', 'quantity', 'product_id', 'type'];

    // ProductReceive.php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
