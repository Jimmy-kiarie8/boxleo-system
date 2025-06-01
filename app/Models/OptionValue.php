<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $fillable = ['option_name', 'option_id'];
    public $with = ['option'];
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
    public function variant_values()
    {
        return $this->hasMany(VariantValue::class);
    }
}
