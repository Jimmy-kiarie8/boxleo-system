<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SellerService extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'service_id', 'amount', 'operating_id'];

    public function create($seller_id, $service_id, $amount)
    {
        SellerService::updateOrCreate([
            'seller_id' => $seller_id,
            'service_id' => $service_id,
            'operating_id' => Auth::user()->ou_id
        ],[
            'amount' => $amount
        ]);
    }
}
