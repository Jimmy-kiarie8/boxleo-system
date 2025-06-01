<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'sku_no',
        'price',
        'quantity',
        'reorder_point',
        'product_id', 'buying_price', 'deleted_at'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    // public static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $number = Sku::max('id') + 1;
    //         $model->sku_no = make_reference_id('SKU', $number);
    //     });
    // }
}

