<?php

namespace App\Modules\Stripe\Services;

use App\Models\Donor;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Stripe\Requests\Payment\CancelStripeSubscriptionRequest;
use App\Modules\Stripe\Requests\Payment\CreateStripePaymentRequest;
use App\Modules\Stripe\Requests\Payment\CreateStripeSubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Invoice;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\StripeClient;

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
    public function createPaymentIntent(array $paymentIntentParameters): PaymentIntent|string
    {
        try {
            return $this->stripe->paymentIntents->create($paymentIntentParameters);

        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }


    /*
     * Retrieve PaymentIntent
     * This method retrieve the payment intent
     * that was created in stripe
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent|string
    {
        try {
            return $this->stripe->paymentIntents->retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }


    /*
     * Confirm PaymentIntent
     * This method confirm the payment intent
     * that created while creating stripe subscription
     */
    public function confirmPaymentIntent(string $paymentIntentId): PaymentIntent|string
    {
        try {
            return $this->stripe->paymentIntents->retrieve($paymentIntentId)->confirm();
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }


    /*
     * Return PaymentIntent Status
     * This method handel the payment intent
     * status if succeeded or need 3D secure (requires_action)
     */
    public function generateIntentResponse(PaymentIntent $intent): JsonResponse
    {
        if ($intent->status === 'succeeded') {
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
