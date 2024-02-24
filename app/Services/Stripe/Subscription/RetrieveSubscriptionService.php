<?php

namespace App\Services\Stripe\Subscription;

use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class RetrieveSubscriptionService extends BaseStripeService
{
    public function retrieve(string $subscriptionId): JsonResponse
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);

            return response()->api(true, 'subscription retrieved successfully', $subscription);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
