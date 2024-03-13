<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stripe\Subscription\CancelSubscriptionRequest;
use App\Modules\Stripe\Services\Subscription\CancelStripeSubscriptionService;
use App\Modules\Subscription\Services\GetSubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private GetSubscriptionService $getSubscriptionService, private CancelStripeSubscriptionService $cancelStripeSubscriptionService)
    {
    }

    public function getUserSubscriptions()
    {
        return $this->getSubscriptionService->getUserSubscriptions();
    }

    public function cancelUserSubscription(CancelSubscriptionRequest $request)
    {
        return $this->cancelStripeSubscriptionService->cancel($request);
    }
}
