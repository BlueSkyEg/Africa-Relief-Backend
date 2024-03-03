<?php

namespace App\Modules\Stripe\Services\Subscription;

use App\Http\Requests\Stripe\Subscription\CreateSubscriptionRequest;
use App\Models\Donor;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\StripeClient;
use Stripe\Subscription;

class CreateSubscriptionService extends BaseStripeService
{
    public function __construct(StripeClient $stripe, private GetDonorService $getDonorService)
    {
        parent::__construct($stripe);
    }

    public function create(CreateSubscriptionRequest $request): JsonResponse
    {
        $donor = $this->getDonorService->getOrCreateDonor($request->name, $request->email, $request->paymentMethodId, true);

        $priceResult = $this->createProductPrice($request);

        if (is_string($priceResult)) {
            return response()->api(false, $priceResult);
        }

        $subscriptionResult = $this->createSubscription($request, $priceResult->id, $donor);

        if (is_string($subscriptionResult)) {
            return response()->api(false, $subscriptionResult);
        }

        $paymentIntentResult = $this->confirmPaymentIntent($subscriptionResult->latest_invoice->payment_intent->id);

        if (is_string($paymentIntentResult)) {
            return response()->api(false, $paymentIntentResult);
        }



        return $this->generateIntentResponse($paymentIntentResult, $subscriptionResult,);
    }

    private function createProductPrice(CreateSubscriptionRequest $request): Price|string
    {
        try {
            return $this->stripe->prices->create([
                'currency' => 'usd',
                'unit_amount' => $request->amount * 100, // The amount in cents
                'recurring' => ['interval' => $request->recurringPeriod],
                'product_data' => ['name' => $request->subscriptionName],
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    private function createSubscription(CreateSubscriptionRequest $request, string $priceId, Donor $donor): Subscription|string
    {
        try {
            return $this->stripe->subscriptions->create([
                'customer' => $donor->stripe_customer_id,
                'items' => [['price' => $priceId]],
                'expand' => ['latest_invoice.payment_intent'],
                'payment_behavior' => 'default_incomplete',
                'default_payment_method' => $request->paymentMethodId,
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    private function confirmPaymentIntent(string $paymentIntentId): PaymentIntent|string
    {
        try {
            return $this->stripe->paymentIntents->retrieve($paymentIntentId)->confirm();
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    private function generateIntentResponse(PaymentIntent $intent, $subscriptionResult): JsonResponse
    {
        if ($intent->status === 'succeeded') {
            return response()->api(true, 'Subscription created successfully', ['intent' => $intent, 'subscription' => $subscriptionResult]);
        }

        if ($intent->status === 'requires_action') {
            return response()->api(false, 'payment method requires action', [
                'requiresAction' => true,
                'clientSecret' => $intent->client_secret,
            ]);
        }

        return response()->api(false, 'Invalid Payment Intent');
    }

    private function createCustomer(string $name, string $email) {
        return $this->stripe->customers->create([
            'name' => $name,
            'email' => $email
        ]);
    }
}
