<?php

namespace App\Models\discounts;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;

class DiscountReward extends Model
{
    protected $fillable = ['value', 'type', 'product_id'];

    protected $hashids = 'main';

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function products()
    {
        return $this->hasMany(DiscountRewardProduct::class);
    }
}
