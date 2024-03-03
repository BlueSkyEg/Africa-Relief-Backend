<?php

namespace App\Modules\Subscription\Services;

use App\Models\Donor;
use App\Modules\Subscription\Repositories\SubscriptionRepository;

class CreateSubscriptionService
{
    public function __construct(private SubscriptionRepository $subscriptionRepository)
    {
    }

    public function createSubscription(\Stripe\Subscription $subscription, Donor $donor, int $donationFormId, int $parentPaymentId)
    {
        return $this->subscriptionRepository->createSubscription($subscription, $donor, $donationFormId, $parentPaymentId);
    }
}
