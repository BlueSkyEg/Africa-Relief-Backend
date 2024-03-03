<?php

namespace App\Modules\Subscription\Services;

use App\Modules\Subscription\Repositories\SubscriptionRepository;

class GetSubscriptionService
{
    public function __construct(private SubscriptionRepository $subscriptionRepository)
    {
    }

    public function getSubscriptionByStripeSubscriptionId(string $stripeSubscriptionId)
    {
        return $this->subscriptionRepository->getSubscriptionByStripeSubscriptionId($stripeSubscriptionId);
    }
}
