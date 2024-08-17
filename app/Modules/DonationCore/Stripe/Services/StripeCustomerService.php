<?php

namespace App\Modules\DonationCore\Stripe\Services;

use App\Modules\DonationCore\Stripe\Exceptions\StripeApiException;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;


class StripeCustomerService extends BaseStripeService
{

    /**
     * @param string $name
     * @param string $email
     * @return Customer
     * @throws StripeApiException
     */
    public function createCustomer(string $name, string $email): Customer
    {
        try {
            return $this->stripe->customers->create([
                'name' => $name,
                'email' => $email
            ]);
        } catch (ApiErrorException $e) {
            throw new StripeApiException("An error occurred while creating new customer on stripe");
        }
    }
}
