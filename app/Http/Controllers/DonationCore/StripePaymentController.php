<?php

namespace App\Http\Controllers\DonationCore;

use App\Http\Controllers\Controller;
use App\Modules\DonationCore\Donor\Exceptions\DonorNotFoundException;
use App\Modules\DonationCore\Stripe\Exceptions\StripeApiException;
use App\Modules\DonationCore\Stripe\Requests\CreateStripeExpressCheckoutRequest;
use App\Modules\DonationCore\Stripe\Requests\CreateStripePaymentRequest;
use App\Modules\DonationCore\Stripe\Resources\StripeIntentResource;
use App\Modules\DonationCore\Stripe\Services\StripePaymentService;
use App\Modules\DonationCore\Stripe\Services\StripeWebhookService;
use App\Modules\DonationCore\Subscription\Resources\SubscriptionResource;
use Illuminate\Http\JsonResponse;

class StripePaymentController extends Controller
{
    public function __construct(
        private readonly StripePaymentService $stripePaymentService,
        private readonly StripeWebhookService $stripeWebhookService
    )
    {
    }


    /**
     * Setup Intent
     * This method creates setup intent has client_secret
     * that clientside would use it to load stripe card elements.
     *
     * @return JsonResponse
     * @throws StripeApiException
     */
    public function setupStripeIntent(): JsonResponse
    {
        $setupIntent = $this->stripePaymentService->setupIntent();

        return response()->success('Setup intent created successfully.', new StripeIntentResource($setupIntent));
    }


    /**
     * Create Stripe Payment
     * This method create payment (OneTime, Subscription) using Card as payment method.
     *
     * @param CreateStripePaymentRequest $request
     * @return JsonResponse
     * @throws StripeApiException
     * @throws DonorNotFoundException
     */
    public function createStripePayment(CreateStripePaymentRequest $request): JsonResponse
    {
        $paymentIntent = $this->stripePaymentService->createStripePayment(array_merge($request->validated(), ['ip' => $request->ip()]));

        return response()->success('Payment intent created successfully.', new StripeIntentResource($paymentIntent));
    }


    /**
     * Create Stripe Express Checkout
     * This method create payment (OneTime, Subscription) using
     * Wallet (GooglePay, ApplePay) as payment method.
     *
     * @param CreateStripeExpressCheckoutRequest $request
     * @return JsonResponse
     * @throws DonorNotFoundException
     * @throws StripeApiException
     */
    public function createStripeExpressCheckout(CreateStripeExpressCheckoutRequest $request): JsonResponse
    {
        $paymentIntent = $this->stripePaymentService->createStripeExpressCheckout(array_merge($request->validated(), ['ip' => $request->ip()]));

        return response()->success('Payment intent created successfully.', new StripeIntentResource($paymentIntent));
    }


    /**
     * Cancel Recurring Payment
     * This method cancel specific subscription
     * (Recurring Payment) on stripe by subscription id
     *
     * @param string $subscriptionId
     * @return JsonResponse
     * @throws StripeApiException
     */
    public function cancelStripeSubscription(string $subscriptionId): JsonResponse
    {
        $subscription = $this->stripePaymentService->cancelStripeSubscription($subscriptionId);

        return response()->success('Subscription canceled successfully.', new SubscriptionResource($subscription));
    }


    /**
     * Trigger Stripe Webhook
     * This method will be called by stripe webhooks to handle the updates that
     * maybe occurs on payments in the stripe dashboard or payments using wallets [Apple Pay, Google Pay].
     *
     * @return void
     */
    public function triggerStripeWebhook(): void
    {
        $this->stripeWebhookService->triggerStripeWebhook();
    }
}
