<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Paypal\CreatePayment;
use App\Paypal\ExecutePayment;

class PaymentController extends Controller
{
    public function create()
    {
        $payment = new CreatePayment;
        return $payment->create();
    }

    public function execute()
    {
        $payment = new ExecutePayment;
        return $payment->execute();
    }
}
