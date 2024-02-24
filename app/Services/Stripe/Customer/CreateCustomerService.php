<?php

namespace App\Services\Stripe\Customer;

use App\Services\Stripe\BaseStripeService;

class CreateCustomerService extends BaseStripeService
{
    public function create($email)
    {
        $customer =  $this->stripe->customers->create([
            'email' => $email
        ]);
        return $customer->id;
    }
}
