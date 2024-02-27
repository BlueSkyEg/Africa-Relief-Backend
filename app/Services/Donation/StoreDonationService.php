<?php

namespace App\Services\Donation;

use App\Models\Donation;
use App\Models\Donor;

class StoreDonationService
{
    /**
     * Create a donation record based on donor and payment intent data.
     *
     * @param Donor $donor The donor associated with the donation
     * @param object $paymentIntentData Payment data received from the payment gateway
     * @return Donation The donation record
     */
    public function createDonationRecord(Donor $donor, object $paymentIntentData): Donation
    {
        // Extracting customer name from full name
        list($firstName, $lastName) = $this->extractCustomerName($paymentIntentData->customer->name);

        // Creating a donation record with data received from the payment intent
        return Donation::create([
            "donor_id"               => $donor->id,
            "subscription_id"        => 0, // Assuming subscription is not applicable for single donations
            "project_title"          => $paymentIntentData->project_title,
            "amount"                 => $paymentIntentData->amount,
            "currency"               => $paymentIntentData->currency,
            "payment_gateway"        => "stripe",
            "payment_transaction_id" => $paymentIntentData->payment_method->id,
            "first_name"             => $firstName,
            "last_name"              => $lastName,
            "phone"                  => $paymentIntentData->payment_method->billing_details->phone,
            "country"                => $paymentIntentData->payment_method->billing_details->address->country,
            "city"                   => $paymentIntentData->payment_method->billing_details->address->city,
            "state"                  => $paymentIntentData->payment_method->billing_details->address->state,
            "zip"                    => $paymentIntentData->payment_method->billing_details->address->postal_code,
            "address1"               => $paymentIntentData->payment_method->billing_details->address->line1,
            "address2"               => $paymentIntentData->payment_method->billing_details->address->line2, // Fixed typo
        ]);
    }

    /**
     * Extract the first name and last name from the full name.
     *
     * @param string $fullName The full name of the customer
     * @return array An array containing the first name and last name
     */
    private function extractCustomerName(string $fullName): array
    {
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;
        return [$firstName, $lastName];
    }
}
