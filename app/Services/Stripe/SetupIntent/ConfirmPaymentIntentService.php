<?php

namespace App\Services\Stripe\SetupIntent;

use App\Http\Requests\Stripe\PaymentIntent\ConfirmPaymentIntentRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class ConfirmPaymentIntentService extends BaseStripeService
{
    public function confirm(PaymentIntent $intent, ConfirmPaymentIntentRequest $request): JsonResponse
    {
        try {
            // Retrieve Subscription Details details
            $subscription = $this->stripe->subscriptions->retrieve($request->subscriptionId);

            // Confirm & Retrieve PaymentIntent with customer and payment_method
            $intent = $this->stripe->paymentIntents->retrieve(
                $request->paymentIntentId,
                ['expand' => ['customer', 'payment_method']]
            )->confirm();

            // Store Transaction at DB
            $this->storePaymentService->processStorePaymentIntoDB($intent, $subscription);

            return response()->api(true, 'payment created successfully');
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
