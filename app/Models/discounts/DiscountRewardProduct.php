<?php

namespace App\Models\discounts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class DiscountRewardProduct extends Model
{
    protected $fillable = ['product_id', 'quantity'];

    protected $hashids = 'main';

    public function reward()
    {
        return $this->belongsTo(DiscountReward::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
