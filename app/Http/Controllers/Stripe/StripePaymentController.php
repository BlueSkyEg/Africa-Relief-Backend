<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Modules\Stripe\Requests\Payment\CreateStripePaymentRequest;
use App\Modules\Stripe\Services\StripeIntentService;
use App\Modules\Stripe\Services\StripePaymentService;
use Illuminate\Http\JsonResponse;

class StripePaymentController extends Controller
{
    public function __construct(
        private readonly StripePaymentService $stripePaymentService,
        private readonly StripeIntentService $stripeIntentService
    )
    {
    }


    /*
     * Setup Intent
     * This method creates setup intent has client_secret
     * that clientside would use it to complete the payment
     */
    public function setupStripeIntent(): JsonResponse
    {
        return $this->stripeIntentService->setupIntent();
    }


    /*
     * Create Stripe Payment
     * Check if payment is recurring payment
     * or one time payment
     */
    public function createStripePayment(CreateStripePaymentRequest $request): JsonResponse
    {
        return $this->stripePaymentService->createStripePayment($request);
    }


    /*
     * Cancel Recurring Payment
     * This method cancel specific subscription
     * (Recurring Payment) on stripe by subscription id
     */
    public function cancelStripeSubscription(string $subscriptionId): JsonResponse
    {
        return $this->stripePaymentService->cancelStripeSubscription($subscriptionId);
    }
}
