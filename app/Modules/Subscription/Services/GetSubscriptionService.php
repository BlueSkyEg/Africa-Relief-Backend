<?php

namespace App\Modules\Subscription\Services;

use App\Modules\Subscription\Repositories\SubscriptionRepository;
use App\Modules\User\Services\GetUserService;

class GetSubscriptionService
{
    public function __construct(private SubscriptionRepository $subscriptionRepository, private GetUserService $getUserService)
    {
    }

    public function getUserSubscriptions()
    {
        $user = $this->getUserService->getAuthUser();
        $subscriptions = $this->subscriptionRepository->getUserSubscriptions($user);

        return response()->api(true, 'Subscriptions retrieved successfully', $subscriptions);
    }

    public function getSubscriptionByStripeSubscriptionId(string $stripeSubscriptionId)
    {
        return $this->subscriptionRepository->getSubscriptionByStripeSubscriptionId($stripeSubscriptionId);
    }
}
