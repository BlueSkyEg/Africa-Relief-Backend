<?php

namespace App\Modules\Stripe\Services;

use Stripe\Customer;
use Stripe\Exception\ApiErrorException;


class StripeCustomerService extends BaseStripeService
{

    public function createCustomer(string $name, string $email): Customer|string
    {
        try {
            return $this->stripe->customers->create([
                'name' => $name,
                'email' => $email
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}
