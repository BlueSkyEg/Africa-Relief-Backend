<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Modules\Donation\Services\GetDonationService;
use App\Modules\Donation\Services\UpdateDonationService;
use App\Modules\Subscription\Services\UpdateSubscriptionService;

class WebhookController extends Controller
{
    public function __construct(
        private UpdateSubscriptionService $updateSubscriptionService,
        private GetDonationService $getDonationService,
        private UpdateDonationService $updateDonationService
    )
    {
    }

    public function listenStripeWebhook()
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        if ($event->type === 'invoice.paid' && $event->data->object->billing_reason === 'subscription_create') {
            $invoice = $event->data->object;
            $subscription = $this->updateSubscriptionService->updateSubscriptionStatus($invoice->subscription, 'active');
            $this->updateDonationService->updateSubscriptionParentDonation($subscription->parent_payment_id, $subscription->id, 'succeeded');
        }

        if ($event->type === 'invoice.paid' && $event->data->object->billing_reason === 'subscription_cycle') {
            $invoice = $event->data->object;
            $subscription = $this->updateSubscriptionService->updateSubscriptionExpiration($invoice->subscription, $invoice->period_end);
            $parentDonation = $this->getDonationService->getDonationById($subscription->parent_payment_id);
            $newRecurringDonation = $parentDonation->replicate()->fill([
                'stripe_transaction_id' => $invoice->payment_intent,
                'payment_amount' => $invoice->amount_paid / 100, // The amount in dollar
                'completed_date' => $invoice->created,
            ]);
            $newRecurringDonation->save();
        }

        if ($event->type === 'customer.subscription.deleted') {
            $subscription = $event->data->object;
            $this->updateSubscriptionService->updateSubscriptionStatus($subscription->id, 'canceled');
        }

        http_response_code(200);
    }
}
