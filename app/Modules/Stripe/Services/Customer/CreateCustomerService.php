<?php

namespace App\Modules\Stripe\Services\Customer;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;


class CreateCustomerService extends BaseStripeService
{

    public function create(): JsonResponse
    {
        try {
            $customer =  $this->stripe->customers->create([
                'name'  => $this->user->name,
                'email' => $this->user->email
            ]);

            // Save customerId at DB
            $this->user->stripe_id = $customer->id;
            $this->user->save();

            return response()->api(true, 'customer created successfully', ['customerId' => $customer->id]);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
