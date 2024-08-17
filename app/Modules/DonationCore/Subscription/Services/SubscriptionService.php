<?php

namespace App\Modules\DonationCore\Subscription\Services;

use App\Modules\DonationCore\Subscription\Repositories\SubscriptionRepository;
use App\Modules\DonationCore\Subscription\Subscription;

class SubscriptionService
{
    public function __construct(private readonly SubscriptionRepository $subscriptionRepository)
    {
    }


    /**
     * @param $id
     * @return Subscription|null
     */
    public function getSubscriptionById($id): ?Subscription
    {
        return $this->subscriptionRepository->getById($id);
    }


    /**
     * @param string $stripeSubscriptionId
     * @return Subscription|null
     */
    public function getSubscriptionByStripeSubscriptionId(string $stripeSubscriptionId): ?Subscription
    {
        return $this->subscriptionRepository->getByStripeSubscriptionId($stripeSubscriptionId);
    }


    /**
     * @param array $attributes
     * @return Subscription
     */
    public function updateOrCreateSubscription(array $attributes): Subscription
    {
        return $this->subscriptionRepository->updateOrCreate($attributes);
    }


    /**
     * @param array $attributes
     * @return Subscription
     */
    public function createSubscription(array $attributes): Subscription
    {
        return $this->subscriptionRepository->create($attributes);
    }
}
