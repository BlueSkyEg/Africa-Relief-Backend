<?php

namespace App\Modules\Stripe\Services;

use App\Http\Requests\Stripe\SingleCharge\CreateSingleChargeRequest;
use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class StripeIntentService extends BaseStripeService
{
    /*
     * Setup Intent
     * This method creates setup intent has client_secret
     * that clientside would use it to complete the payment
     */
    public function setupIntent(): JsonResponse
    {
        try {
            $intent = $this->stripe->setupIntents->create([
                'payment_method_types' => ['card'],
            ]);

            return response()->api(true, 'intent created successfully', $intent);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    /*
     * Create Payment Intent
     * This method create payment intent and confirm
     * the payment automatically after create the intent
     * then return the PaymentIntent Object with its status
     */
    public function createPaymentIntent(array $paymentIntentAttributes): PaymentIntent|string
    {
        try {
            return $this->stripe->paymentIntents->create($paymentIntentAttributes);

        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}
