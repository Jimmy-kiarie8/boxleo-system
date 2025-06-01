<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'order_no', 'updated'];

}
