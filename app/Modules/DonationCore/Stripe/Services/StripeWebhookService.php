<?php

namespace App\Modules\DonationCore\Stripe\Services;

use App\Modules\DonationCore\Donation\Services\DonationService;
use App\Modules\DonationCore\Donor\Services\DonorService;
use App\Modules\DonationCore\PaymentMethod\Services\PaymentMethodService;
use App\Modules\DonationCore\Subscription\Services\SubscriptionService;
use Illuminate\Support\Facades\Log;

class StripeWebhookService
{
    public function __construct(
        private readonly DonorService         $donorService,
        private readonly DonationService      $donationService,
        private readonly SubscriptionService  $subscriptionService,
        private readonly PaymentMethodService $paymentMethodService,
    )
    {
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
        $endpoint_secret = config('stripe.webhook_secret_key');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::debug("An error occurred in stripe webhook payload: " . $e->getMessage());
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::debug("An error occurred in stripe webhook signature: " . $e->getMessage());
            http_response_code(400);
            exit();
        }

        // Handle the event
        $this->handleStripeEvents($event->type, $event->data->object);

        http_response_code(200);
    }


    /**
     * @param $eventType
     * @param $eventObject
     * @return void
     */
    private function handleStripeEvents($eventType, $eventObject)
    {
        switch ($eventType) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceededEvent($eventObject);
                break;
            case 'invoice.paid':
                $this->handleInvoicePaidEvent($eventObject);
                break;
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailedEvent($eventObject);
                break;
            case 'charge.refunded':
                $this->handleChargeRefundedEvent($eventObject);
                break;
            case 'customer.subscription.deleted':
                $this->handleCustomerSubscriptionDeletedEvent($eventObject);
                break;
        }
    }


    /**
     * This event occurs when a PaymentIntent has successfully completed payment.
     *
     * @param $eventObject
     * @return void
     */
    private function handlePaymentIntentSucceededEvent($eventObject): void
    {
        if ($eventObject->description !== 'Subscription update' && $eventObject->statement_descriptor === 'AFRICA-RELIEF.ORG') {
            $payment = $eventObject->charges->data[0];
            $paymentMethodDetails = $payment->payment_method_details->card;

            $donor = $this->donorService->getDonorByStripeCustomerId($payment->customer);

            $paymentMethodData = [
                [
                    'donor_id' => $donor?->id,
                    'fingerprint_id' => $paymentMethodDetails->fingerprint,
                ],
                [
                    'stripe_payment_method_id' => $payment->payment_method,
                    'exp_month' => $paymentMethodDetails->exp_month,
                    'exp_year' => $paymentMethodDetails->exp_year,
                    'last4' => $paymentMethodDetails->last4,
                    'brand' => $paymentMethodDetails->brand,
                    'wallet' => $paymentMethodDetails->wallet?->type,
                    'name' => $payment->billing_details->name,
                    'email' => $payment->billing_details->email,
                    'phone' => $payment->billing_details->phone,
                    'country' => $payment->billing_details->address->country,
                    'city' => $payment->billing_details->address->city,
                    'state' => $payment->billing_details->address->state,
                    'street_address' => $payment->billing_details->address->line1,
                    'postal_code' => $payment->billing_details->address->postal_code,
                    'setup_for_future_use' => (bool)$eventObject->setup_future_usage
                ]
            ];
            $paymentMethod = $this->paymentMethodService->updateOrCreatePaymentMethod($paymentMethodData);

            $donationData = [
                ['id' => $payment->metadata['Donation Post ID']],
                [
                    'payment_method_id' => $paymentMethod->id,
                    'stripe_transaction_id' => $eventObject->id,
                    'completed_date' => $payment->created,
                    'status' => $payment->status,
                    'live_mode' => $payment->livemode,
                ]
            ];
            $this->donationService->updateOrCreateDonation($donationData);
        }
    }


    /**
     * This event occurs whenever an invoice payment attempt succeeds or an invoice is marked as paid out-of-band.
     *
     * @param $eventObject
     * @return void
     */
    private function handleInvoicePaidEvent($eventObject): void
    {
        if ($eventObject->billing_reason === 'subscription_create' && $eventObject->statement_descriptor === 'AFRICA-RELIEF.ORG') {
            $recurringPeriod = $eventObject->lines->data[0]->period;

            $subscriptionData = [
                [
                    'stripe_subscription_id' => $eventObject->subscription
                ],
                [
                    'completed_date' => $recurringPeriod->start,
                    'expiration_date' => $recurringPeriod->end,
                    'status' => 'active',
                    'live_mode' => $eventObject->livemode
                ]
            ];
            $this->subscriptionService->updateOrCreateSubscription($subscriptionData);
        } else if ($eventObject->billing_reason === 'subscription_cycle') {
            $recurringPeriod = $eventObject->lines->data[0]->period;

            $parentDonation = $this->donationService->getDonationById($eventObject->subscription_details->metadata['Donation Post ID']);

            if ($parentDonation) {
                $subscription = $parentDonation->subscription;
                $subscription->completed_date = $recurringPeriod->start;
                $subscription->expiration_date = $recurringPeriod->end;
                $subscription->save();

                $newRecurringDonation = $parentDonation?->replicate()->fill([
                    'stripe_transaction_id' => $eventObject->payment_intent,
                    'amount' => $eventObject->amount_paid / 100, // The amount in dollar
                    'completed_date' => $eventObject->created,
                ]);
                $newRecurringDonation->save();
            }
        }
    }


    /**
     * This event occurs whenever an invoice payment attempt fails, due either to a declined payment or to the lack of a stored payment method.
     *
     * @param $eventObject
     * @return void
     */
    private function handleInvoicePaymentFailedEvent($eventObject): void
    {
        if ($eventObject->billing_reason === 'subscription_cycle' && $eventObject->statement_descriptor === 'AFRICA-RELIEF.ORG') {
            $parentDonation = $this->donationService->getDonationById($eventObject->subscription_details->metadata['Donation Post ID']);

            if ($parentDonation) {
                $subscription = $parentDonation->subscription;
                $subscription->status = 'inactive';
                $subscription->save();
            }
        }
    }


    /**
     * This event occurs whenever a charge is refunded, including partial refunds.
     *
     * @param $eventObject
     * @return void
     */
    private function handleChargeRefundedEvent($eventObject): void
    {
        if ($eventObject->statement_descriptor === 'AFRICA-RELIEF.ORG') {
            // Get donation by payment_intent
            $donation = $this->donationService->getDonationByStripeTransactionId($eventObject->payment_intent);

            if ($donation) {
                $donation->status = 'refunded';
                $donation->save();

                // Check if donation has subscription
                if ($donation->subscription_id) {
                    $subscription = $donation->subscription;
                    $subscription->status = 'inactive';
                    $subscription->save();
                }
            }
        }
    }


    /**
     * This event occurs whenever a customer's subscription ends.
     *
     * @param $eventObject
     * @return void
     */
    private function handleCustomerSubscriptionDeletedEvent($eventObject): void
    {
        $subscription = $this->subscriptionService->getSubscriptionByStripeSubscriptionId($eventObject->id);

        if ($subscription) {
            $subscription->status = 'cancelled';
            $subscription->save();
        }
    }
}
