<?php

namespace App\Modules\Subscription\Repositories;

use App\Models\Donor;
use App\Models\Subscription;

class SubscriptionRepository
{
    public function createSubscription(\Stripe\Subscription $subscription, Donor $donor, string $donationFormId)
    {
        return Subscription::create([
            'donor_id' => $donor->id,
            'period' => $subscription->,
            'initial_amount' => $subscription->,
            'recurring_amount' => $subscription->,
            'stripe_subscription_id' => $subscription->id,
            'parent_payment_id' => $subscription->,
            'donation_form_id' => $donationFormId,
            'created' => $subscription->,
            'expiration' => $subscription->,
            'status' => $subscription->,
            'notes'
        ]);
    }

    public function getSubscriptionByStripeId(string $stripeSubscriptionId)
    {
        return Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();
    }
}
