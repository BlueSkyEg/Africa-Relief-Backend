<?php

namespace App\Modules\DonationCore\Subscription\Repositories;

use App\Modules\DonationCore\Subscription\Subscription;

class SubscriptionRepository
{

    /**
     * @param $id
     * @return Subscription|null
     */
    public function getById($id): ?Subscription
    {
        return Subscription::find($id);
    }


    /**
     * @param string $stripeSubscriptionId
     * @return Subscription|null
     */
    public function getByStripeSubscriptionId(string $stripeSubscriptionId): ?Subscription
    {
        return Subscription::where('stripe_subscription_id', $stripeSubscriptionId)->first();
    }


    /**
     * @param array $attributes
     * @return Subscription
     */
    public function updateOrCreate(array $attributes): Subscription
    {
        return Subscription::updateOrCreate(...$attributes);
    }


    /**
     * @param array $attributes
     * @return Subscription
     */
    public function create(array $attributes): Subscription
    {
        return Subscription::create($attributes);
    }
}
