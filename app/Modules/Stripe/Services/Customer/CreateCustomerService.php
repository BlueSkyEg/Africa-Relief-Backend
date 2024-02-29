<?php

namespace App\Modules\Stripe\Services\Customer;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;


class CreateCustomerService extends BaseStripeService
{

    public function create(string $name, string $email): JsonResponse
    {
        try {
            $customer =  $this->stripe->customers->create([
                'name' => $name,
                'email' => $email
            ]);

            return response()->api(true, 'Customer created successfully', ['customerId' => $customer->id]);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
