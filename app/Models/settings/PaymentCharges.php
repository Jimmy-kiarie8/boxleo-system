<?php

namespace App\Models\settings;

use App\Models\Sale;
use App\Models\User;
use App\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCharges extends Model
{
    use HasFactory;

    public function calculate()
    {
        $users = User::count();
        $orders = Sale::count();
        $portal = Seller::count();
    }
}
