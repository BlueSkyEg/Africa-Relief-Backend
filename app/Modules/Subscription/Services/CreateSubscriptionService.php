<?php

namespace App\Modules\Subscription\Services;

use App\Models\Donation;
use App\Models\Donor;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Subscription\Repositories\SubscriptionRepository;
use Stripe\Invoice;

class CreateSubscriptionService
{
    public function __construct(private readonly SubscriptionRepository $subscriptionRepository)
    {
    }

    public function createSubscription(Invoice $invoice, Donation $donation)
    {
        $lineItemObj = $invoice->lines->data[0];
        return $this->subscriptionRepository->createSubscription([
            'donor_id' => $donation->donor_id,
            'period' => $lineItemObj->plan->interval,
            'initial_amount' => $lineItemObj->plan->amount / 100, // The amount in dollar
            'recurring_amount' => $lineItemObj->plan->amount / 100, // The amount in dollar
            'stripe_subscription_id' => $lineItemObj->subscription,
            'parent_payment_id' => $donation->id,
            'donation_form_id' => $lineItemObj->metadata['Donation Form Id'],
            'created_at' => $lineItemObj->period['start'],
            'expiration_at' => $lineItemObj->period['end'],
            'status' => 'active',
            //'notes'
        ]);
    }
}
