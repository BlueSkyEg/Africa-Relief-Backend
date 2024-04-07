<?php

namespace App\Modules\Subscription\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function getUserSubscriptions($user)
    {
        return $user->subscriptions->load('donationForm');
    }

    public function getSubscriptionByStripeSubscriptionId(string $stripeSubscriptionId)
    {
        return Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();
    }

    public function createSubscription(array $subscriptionParameters )
    {
        return Subscription::create($subscriptionParameters);
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
