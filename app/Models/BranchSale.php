<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BranchSale extends Model
{
    public $fillable = ['branch_id', 'sale_id', 'delivery_status', 'receive_status', 'sent', 'received', 'comment', 'products'];

    public function getProductsAttribute($value)
    {
        return unserialize($value);
    }
    public function setProductsAttribute($value)
    {
        $this->attributes['products'] = serialize($value);
    }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('d M Y');
    }
}
