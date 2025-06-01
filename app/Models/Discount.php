<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{

    public function sets()
    {
        return $this->hasMany(DiscountCriteriaSet::class);
    }

    public function rewards()
    {
        return $this->hasMany(DiscountReward::class);
    }

    public function items()
    {
        return $this->hasManyThrough(DiscountCriteriaItem::class, DiscountCriteriaSet::class);
    }
}
