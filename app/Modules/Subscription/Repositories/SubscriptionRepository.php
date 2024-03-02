<?php

namespace App\Modules\Subscription\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function getSubscriptionByStripeId(string $stripeSubscriptionId)
    {
        return Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();
    }
}
