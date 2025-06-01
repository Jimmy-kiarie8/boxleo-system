<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippinglabel extends Model
{
    use HasFactory;
    protected $fillable = ['label_id', 'sale_id'];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

}
