<?php

namespace App\Services;

use App\Models\Subscription;

class SubscriptionService
{
    public function processSubscription(array $data)
    {
        // Process the subscription and store it in the database
        $subscription = Subscription::create($data);

        if ($subscription) {
            return ['message' => 'Subscription processed successfully', 'subscription' => $subscription];
        } else {
            return ['error' => 'Failed to process subscription'];
        }
    }
}
