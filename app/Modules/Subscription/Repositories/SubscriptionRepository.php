<?php

namespace App\Modules\Subscription\Repositories;

use App\Models\Donor;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionRepository
{
    public function getUserSubscriptions($user)
    {
        return $user->subscriptions;
    }

    public function getSubscriptionByStripeSubscriptionId(string $stripeSubscriptionId)
    {
        return Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();
    }

    public function createSubscription(\Stripe\Subscription $subscription, Donor $donor, int $donationFormId, int $parentPaymentId)
    {
        return Subscription::create([
            'donor_id' => $donor->id,
            'period' => $subscription->plan->interval,
            'initial_amount' => $subscription->plan->amount / 100, // The amount in dollar
            'recurring_amount' => $subscription->plan->amount / 100, // The amount in dollar
            'stripe_subscription_id' => $subscription->id,
            'parent_payment_id' => $parentPaymentId,
            'donation_form_id' => $donationFormId,
            'created_at' => $subscription->current_period_start,
            'expiration_at' => $subscription->current_period_end,
            'status' => $subscription->status,
            //'notes'
        ]);
    }

    public function updateSubscriptionStatus(string $stripeSubscriptionId, string $status)
    {
        $subscription = $this->getSubscriptionByStripeSubscriptionId($stripeSubscriptionId);
        $subscription->status = $status;
        $subscription->save();
        return $subscription;
    }

    public function updateSubscriptionExpiration(string $stripeSubscriptionId, int $expiration)
    {
        $subscription = $this->getSubscriptionByStripeSubscriptionId($stripeSubscriptionId);
        $subscription->expiration = $expiration;
        $subscription->save();
        return $subscription;
    }
}
