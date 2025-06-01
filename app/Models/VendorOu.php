<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOu extends Model
{
    use HasFactory;
    protected $fillable = ['vendor_id', 'ou_id'];
    
    public function create($vendor_id, $ou_id)
    {
        VendorOu::updateOrCreate([
            'vendor_id' => $vendor_id,
            'ou_id' => $ou_id
        ]);
    }
}
