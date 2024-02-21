<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\Subscription\CancelSubscriptionRequest;
use App\Http\Requests\Stripe\Subscription\CreateSubscriptionRequest;
use App\Modules\Stripe\Services\Subscription\CancelSubscriptionService;
use App\Modules\Stripe\Services\Subscription\CreateSubscriptionService;
use App\Modules\Stripe\Services\Subscription\RetrieveSubscriptionService;
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
