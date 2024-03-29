<?php

namespace App\Services\Stripe\Subscription;

use App\Http\Requests\Stripe\Subscription\CancelSubscriptionRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class CancelSubscriptionService extends BaseStripeService
{
    public function cancel(CancelSubscriptionRequest $request): JsonResponse
    {
        try {
            $this->stripe->subscriptions->cancel($request->subscriptionId);

            return response()->api(true, 'subscription canceled successfully');
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
