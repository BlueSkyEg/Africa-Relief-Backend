<?php

namespace App\Services\Stripe\SingleCharge;

use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class CreateSingleChargeService extends BaseStripeService
{
    public function create(CreateSingleChargeRequest $request): JsonResponse
    {
        try {
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $request->amount * 100, // The amount in cents
                'currency' => 'usd',
                'customer' => $this->stripeCustomerId ?? $request->customerId,
                'payment_method' => $request->paymentMethodId,
                'description' => $request->paymentDescription,
                'payment_method_types' => ['card'],
                'confirm' => true,
                'confirmation_method' => 'manual',
                'use_stripe_sdk' => true,
                ['expand' => ['customer', 'payment_method']]
            ]);

            return $this->generateIntentResponse($intent);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    private function generateIntentResponse(PaymentIntent $intent): JsonResponse
    {

        if ($intent->status === 'succeeded') {
            // Store Transaction at DB
            $this->storePaymentService->processStorePaymentIntoDB($intent);
            return response()->api(true, 'payment created successfully');
        }

        if ($intent->status === 'requires_action') {
            return response()->api(false, 'payment method requires action', [
                'requiresAction' => true,
                'clientSecret' => $intent->client_secret,
            ]);
        }

        return response()->api(false, 'Invalid Payment Intent');
    }
}
