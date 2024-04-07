<?php

namespace App\Modules\Stripe\Services;

use App\Models\Donor;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Stripe\Requests\Payment\CancelStripeSubscriptionRequest;
use App\Modules\Stripe\Requests\Payment\CreateStripePaymentRequest;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Invoice;
use Stripe\Price;
use Stripe\StripeClient;

class StripePaymentService extends BaseStripeService
{
    public function __construct(
        StripeClient $stripe,
        private readonly StripeIntentService $stripeIntentService,
        private readonly GetDonorService $getDonorService
    )
    {
        parent::__construct($stripe);
    }

    /*
     * Create Stripe Payment
     * Check if payment is recurring payment
     * or one time payment
     */
    public function createStripePayment(CreateStripePaymentRequest $request): JsonResponse
    {
        if ($request->filled('recurringPeriod')) {
            return $this->createStripeSubscription($request);
        }

        return $this->createStripeOneTimePayment($request);
    }


    /*
     * Cancel Recurring Payment
     * This method cancel specific subscription
     * (Recurring Payment) on stripe by subscription id
     */
    public function cancelStripeSubscription(string $subscriptionId): JsonResponse
    {
        try {
            $this->stripe->subscriptions->cancel($subscriptionId);

            return response()->api(true, 'subscription canceled successfully');
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }


    /*
     * Confirm One Time Payment
     * This method confirm payment with stripe
     * and return the payment intent status
     */
    private function createStripeOneTimePayment(CreateStripePaymentRequest $request): JsonResponse
    {
        $donor = $this->getDonorService->getOrCreateDonor($request->name, $request->email, $request->paymentMethodId, $request->savePaymentMethod);

        $intentResult = $this->stripeIntentService->createPaymentIntent([
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'statement_descriptor' => 'AFRICA-RELIEF.ORG',
            'confirm' => true, // Confirm the payment automatically
            'amount' => $request->amount * 100, // The amount in cents
            'customer' => $donor->stripe_customer_id,
            'payment_method' => $request->paymentMethodId,
            'description' => $request->donationFormTitle,
            'metadata' => [
                'Donation Form Id' => $request->donationFormId,
                'Email' => $donor->email,
                'Anonymous Donation' => $request->anonymousDonation,
                'Comment' => $request->billingComment
            ]
        ]);
        if (is_string($intentResult)) {
            return response()->api(false, $intentResult);
        }

        return $this->stripeIntentService->generateIntentResponse($intentResult);
    }


    /*
     * Create Recurring Payments
     * This method create subscription (Recurring Payment)
     *  with stripe and return the payment intent status
     */
    private function createStripeSubscription(CreateStripePaymentRequest $request): JsonResponse
    {
        $donor = $this->getDonorService->getOrCreateDonor($request->name, $request->email, $request->paymentMethodId, $request->savePaymentMethod);

        $priceResult = $this->createProductPrice($request);
        if (is_string($priceResult)) {
            return response()->api(false, $priceResult);
        }

        $subscriptionResult = $this->createSubscription($request, $priceResult->id, $donor);
        if (is_string($subscriptionResult)) {
            return response()->api(false, $subscriptionResult);
        }

        $invoiceResult = $this->addStatementDescriptorToInvoice($subscriptionResult->latest_invoice->id);
        if (is_string($invoiceResult)) {
            return response()->api(false, $invoiceResult);
        }

        $paymentIntentResult = $this->stripeIntentService->confirmPaymentIntent($invoiceResult->payment_intent);
        if (is_string($paymentIntentResult)) {
            return response()->api(false, $paymentIntentResult);
        }

        return $this->stripeIntentService->generateIntentResponse($paymentIntentResult);
    }


    /*
     * Create Product Price
     * This method create plan on stripe
     * that customer can subscribe to it
     */
    private function createProductPrice(CreateStripePaymentRequest $request): Price|string
    {
        try {
            return $this->stripe->prices->create([
                'currency' => 'usd',
                'unit_amount' => $request->amount * 100, // The amount in cents
                'recurring' => ['interval' => $request->recurringPeriod],
                'product_data' => ['name' => $request->donationFormTitle],
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }


    /*
     * Create Subscription
     * This method create subscription on stripe
     * for a specific plan and by default the subscription
     * status will be incomplete to handel requires_action if exists
     */
    private function createSubscription(CreateStripePaymentRequest $request, string $priceId, Donor $donor): \Stripe\Subscription|string
    {
        try {
            return $this->stripe->subscriptions->create([
                'customer' => $donor->stripe_customer_id,
                'items' => [['price' => $priceId]],
                'expand' => ['latest_invoice.payment_intent.payment_method'],
                'payment_behavior' => 'default_incomplete',
                'default_payment_method' => $request->paymentMethodId,
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'metadata' => [
                    'Donation Form Id' => $request->donationFormId,
                    'Email' => $donor->email,
                    'Anonymous Donation' => $request->anonymousDonation,
                    'Comment' => $request->billingComment
                ]
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }


    /*
     * Add Statement Descriptor To Invoice
     * This method add statement descriptor (AFRICA-RELIEF.ORG)
     * to can identify the payments that created by website
     */
    private function addStatementDescriptorToInvoice(string $invoiceId): Invoice|string
    {
        try {
            return $this->stripe->invoices->update(
                $invoiceId,
                ['statement_descriptor' => 'AFRICA-RELIEF.ORG']
            );
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}
