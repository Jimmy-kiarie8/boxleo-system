<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public function getRatesAttribute($value)
    {
        return unserialize($value);
    }
    public function setRatesAttribute($value)
    {
        $this->attributes['rates'] = serialize($value);
    }

    public function rates()
    {

    }
}
