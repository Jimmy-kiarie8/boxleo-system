<?php

namespace App\Models;

use App\Seller;
use Illuminate\Database\Eloquent\Model;

class Storedetails extends Model
{
    protected $fillable = ['account_name', 'account_no', 'address', 'address_2', 'business_no', 'company_address', 'company_email', 'company_name', 'company_phone', 'company_website', 'email', 'mpesa_name', 'mpesa_phone', 'name', 'payment_mode', 'phone', 'postal_code', 'seller_id'];

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }
}
