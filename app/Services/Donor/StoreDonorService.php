<?php

namespace App\Services\Donor;

use App\Models\Donor;

class StoreDonorService
{
    /**
     * Create or update a donor record based on payment intent data.
     *
     * @param object $paymentIntentData Payment data received from stripe payment intent class
     * @return Donor The donor record
     */
    public function createOrUpdateDonorRecord(object $paymentIntentData): Donor
    {
        // Creating or updating a donor record with the provided payment intent data
        return Donor::updateOrCreate(
            ['stripe_customer_id' => $paymentIntentData->customer->id],
            [
                'email' => $paymentIntentData->customer->email,
                'user_id' => 0,
            ]
        );
    }
}
