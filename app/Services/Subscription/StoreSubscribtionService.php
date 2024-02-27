<?php

namespace App\Services\Subscription;

use App\Models\Donation;
use App\Models\Donor;
use App\Models\Subscription;

class StoreSubscribtionService
{
    /**
     * Create a subscription record based on donor, donation, and subscription data.
     *
     * @param Donor $donor The donor associated with the subscription
     * @param Donation $donation The donation associated with the subscription
     * @param mixed $subscriptionData The subscription data received from Stripe
     * @return Subscription The subscription record
     */
    public function createSubscriptionRecord(Donor $donor, Donation $donation, $subscriptionData): Subscription
    {
        // Creating a subscription record with data received from Stripe
        return Subscription::create([
            "donor_id"                => $donor->id,
            "donation_id"             => $donation->id,
            "amount"                  => $donation->amount,
            "stripe_subscription_id"  => $subscriptionData->id,
            "period"                  => $subscriptionData->plan->interval,
            "start_date"              => date('Y-m-d H:i:s', $subscriptionData->current_period_start),
            "end_date"                => date('Y-m-d H:i:s', $subscriptionData->current_period_end),
            "status"                  => "active",
            "notes"                   => "notes",
        ]);
    }
}
