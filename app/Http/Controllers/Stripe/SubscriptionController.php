<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\Subscription\CancelSubscriptionRequest;
use App\Http\Requests\Stripe\Subscription\CreateSubscriptionRequest;
use App\Services\Stripe\Subscription\CancelSubscriptionService;
use App\Services\Stripe\Subscription\CreateSubscriptionService;
use App\Services\Stripe\Subscription\RetrieveSubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function createSubscription(CreateSubscriptionService $createSubscriptionService, CreateSubscriptionRequest $request): JsonResponse
    {
        return $createSubscriptionService->create($request);
    }

    public function cancelSubscription(CancelSubscriptionService $cancelSubscriptionService, CancelSubscriptionRequest $request): JsonResponse
    {
        return $cancelSubscriptionService->cancel($request);
    }

    public function retrieveSubscription(RetrieveSubscriptionService $retrieveSubscriptionService, string $subscriptionId): JsonResponse
    {
        return $retrieveSubscriptionService->retrieve($subscriptionId);
    }
}
