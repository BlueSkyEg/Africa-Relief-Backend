<?php

namespace App\Modules\Subscription\Services;

use App\Modules\Subscription\Repositories\SubscriptionRepository;

class UpdateSubscriptionService
{
    public function __construct(private SubscriptionRepository $subscriptionRepository)
    {
    }

    public function updateSubscriptionStatus(string $stripeSubscriptionId, string $status)
    {
        return $this->subscriptionRepository->updateSubscriptionStatus($stripeSubscriptionId, $status);
    }

    public function updateSubscriptionExpiration(string $stripeSubscriptionId, int $expiration)
    {
        return $this->subscriptionRepository->updateSubscriptionExpiration($stripeSubscriptionId, $expiration);
    }
}
