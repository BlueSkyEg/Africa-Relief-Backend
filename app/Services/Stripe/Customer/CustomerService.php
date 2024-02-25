<?php

namespace App\Services\Stripe\Customer;

use App\Services\Stripe\BaseStripeService;

class CustomerService extends BaseStripeService
{
    public function create($request)
    {
        $customer =  $this->stripe->customers->create([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return $customer->id;
    }
}
