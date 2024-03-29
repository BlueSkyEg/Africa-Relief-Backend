<?php

namespace App\Services\Stripe\SetupIntent;

use App\Http\Requests\Stripe\PaymentIntent\CreatePaymentIntentRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class CreateSetupIntentService extends BaseStripeService
{
    public function create(CreatePaymentIntentRequest $request): JsonResponse
    {
        try {
            $intent = $this->stripe->setupIntents->create([
                'customer' => $this->stripeCustomerId ?? $request->customerId,
                'payment_method_types' => ['card'],
            ]);

            return response()->api(true, 'intent created successfully', ['clientSecret' => $intent->client_secret]);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
