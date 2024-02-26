<?php

namespace App\Services;

use App\Models\Donor;
use App\Models\Donation;
use App\Models\Subscription;
use Tymon\JWTAuth\Facades\JWTAuth;

class StorePaymentService
{
    /**
     * Process and store payment information into the database.
     *
     * @param array $transactionData Payment data received from the payment gateway
     * @return void
     */
    public function processStorePaymentIntoDB($transactionData, $subscriptiondata = null)
    {
        // If the user is authenticated, retrieve their donor record
        $user = JWTAuth::user();
        if ($user) {
            $donor = $user->donor;
        } else {
            // If guest authenticated, create or update the donor record based on Stripe customer data
            $donor = $this->createOrUpdateDonorRecord($transactionData);
        }

        // Create a donation record
        $donation = $this->createDonationRecord($donor, $transactionData);

        // Create Subscription record
        if ($subscriptiondata) {
            $this->createSubscriptionRecord($donor, $donation, $subscriptiondata);
        }
    }


    private function extractCustomerName($fullName)
    {
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;
        return [$firstName, $lastName];
    }

    private function createOrUpdateDonorRecord($transactionData)
    {
        return Donor::updateOrCreate(
            ['stripe_customer_id' => $transactionData['customer']['id']],
            [
                'email' => $transactionData['customer']['email'],
                'user_id' => 0, // Assuming user_id 0 indicates a non-user donor
            ]
        );
    }

    private function createDonationRecord($donor, $transactionData)
    {
        list($firstName, $lastName) = $this->extractCustomerName($transactionData["customer"]["name"]);

        return Donation::create([
            "donor_id" => $donor->id,
            "subscription_id" => 0, // Assuming subscription is not applicable for single donations
            "project_title" => $transactionData["project_title"],
            "amount" => $transactionData["amount"],
            "currency" => $transactionData["currency"],
            "payment_gateway" => "stripe",
            "payment_transaction_id" => $transactionData["payment_method"]["id"],
            "first_name" => $firstName,
            "last_name" => $lastName,
            "phone" => $transactionData["payment_method"]["billing_details"]["phone"],
            "country" => $transactionData["payment_method"]["billing_details"]["address"]["country"],
            "city" => $transactionData["payment_method"]["billing_details"]["address"]["city"],
            "state" => $transactionData["payment_method"]["billing_details"]["address"]["state"],
            "zip" => $transactionData["payment_method"]["billing_details"]["address"]["postal_code"],
            "address1" => $transactionData["payment_method"]["billing_details"]["address"]["line1"],
            "address2" => $transactionData["payment_method"]["billing_details"]["address"]["line2"], // Fixed typo
        ]);
    }
    private function createSubscriptionRecord($donor, $donation, $subscriptiondata)
    {
        $subscription = Subscription::create([
            "donor_id"                => $donor->id,
            "donation_id"             => $donation->id,
            "amount"                  => $donation->amount,
            "stripe_subscription_id"  => $subscriptiondata->id,
            "period"                  => $subscriptiondata->plan->interval,
            "start_date"              => date('Y-m-d H:i:s', $subscriptiondata->current_period_start),
            "end_date"                => date('Y-m-d H:i:s', $subscriptiondata->current_period_end),
            "status"                  => "active",
            "notes"                   => "notes",
        ]);
    }
}
