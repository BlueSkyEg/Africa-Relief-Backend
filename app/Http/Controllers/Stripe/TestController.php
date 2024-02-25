<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function createPaymentMethod(){
        return view('stripe.create-payment-method');
    }
}
