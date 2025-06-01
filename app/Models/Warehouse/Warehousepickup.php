<?php

namespace App\Models\Warehouse;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehousepickup extends Model
{
    use HasFactory;

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }
    public function getDataAttribute($value)
    {
        return unserialize($value);
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
}
