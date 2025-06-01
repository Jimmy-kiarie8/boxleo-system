<?php

namespace App\Models\discounts;

use Illuminate\Database\Eloquent\Model;

class DiscountCriteriaModel extends Model
{
    public function criteria()
    {
        return $this->belongsTo(DiscountCriteriaItem::class, 'discount_criteria_item_id');
    }
}
