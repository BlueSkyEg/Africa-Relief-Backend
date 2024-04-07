<?php

namespace App\Http\Controllers;

use App\Modules\Subscription\Services\GetSubscriptionService;

class SubscriptionController extends Controller
{
    public function __construct(private GetSubscriptionService $getSubscriptionService)
    {
    }

    public function getUserSubscriptions()
    {
        return $this->getSubscriptionService->getUserSubscriptions();
    }
}
