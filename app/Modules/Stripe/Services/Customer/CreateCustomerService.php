<?php

namespace App\Modules\Stripe\Services\Customer;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;


class CreateCustomerService extends BaseStripeService
{

    public function create(string $name, string $email): Customer|string
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
