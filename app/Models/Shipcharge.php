<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipcharge extends Model
{
    use HasFactory;
    // public $table = ['shipcharges'];
    
    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    // }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
