<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sale_id',
        'product_id',
        'quantity',
        'transaction_type',
        'comment',
        'ou_id'
    ];
}
