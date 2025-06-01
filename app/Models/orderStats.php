<?php

namespace App\Models;

use App\Scopes\StatsScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderStats extends Model
{
    use HasFactory;


    public static function booted()
    {
        static::addGlobalScope(new StatsScope);
    }

}
