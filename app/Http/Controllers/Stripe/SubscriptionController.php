<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\Subscription\CancelSubscriptionRequest;
use App\Http\Requests\Stripe\Subscription\CreateSubscriptionRequest;
use App\Modules\Stripe\Services\Subscription\CancelStripeSubscriptionService;
use App\Modules\Stripe\Services\Subscription\CreateStripeSubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function createSubscription(CreateStripeSubscriptionService $createSubscriptionService, CreateSubscriptionRequest $request): JsonResponse
    {
        return $createSubscriptionService->create($request);
    }

    public function cancelSubscription(CancelStripeSubscriptionService $cancelSubscriptionService, CancelSubscriptionRequest $request): JsonResponse
    {
        return $cancelSubscriptionService->cancel($request);
    }
}
