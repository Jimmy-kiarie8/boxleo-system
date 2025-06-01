<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option_name'];
    // public $with = ['option_values'];
    public function product_options()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function variant_values()
    {
        return $this->hasMany(VariantValue::class);
    }
    public function option_values()
    {
        return $this->hasMany(OptionValue::class);
    }
}
