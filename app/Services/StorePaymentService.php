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
     * @param array $data Payment data received from the payment gateway
     * @return void
     */
    public function processStorePaymentIntoDB($data)
    {
        // If the user is authenticated, retrieve their donor record
        $user = JWTAuth::user();
        if ($user) {
            $donor = $user->donor;
        } else {
            // If the user is not authenticated, create or update the donor record based on Stripe customer data
            $donor = $this->createOrUpdateDonor($data);
        }

        // Create a donation record
        $this->createDonation($donor, $data);
    }

   
    private function createOrUpdateDonor($data)
    {
        return Donor::updateOrCreate(
            ['stripe_customer_id' => $data['customer']['id']],
            [
                'email' => $data['customer']['email'],
                'user_id' => 0, // Assuming user_id 0 indicates a non-user donor
            ]
        );
    }

    private function createDonation($donor, $data)
    {
        list($firstName, $lastName) = $this->extractCustomerName($data["customer"]["name"]);

        Donation::create([
            "donor_id" => $donor->id,
            "subscription_id" => 0, // Assuming subscription is not applicable for single donations
            "project_title" => $data["project_title"],
            "amount" => $data["amount"],
            "currency" => $data["currency"],
            "payment_gateway" => "stripe",
            "payment_transaction_id" => $data["payment_method"]["id"],
            "first_name" => $firstName,
            "last_name" => $lastName,
            "phone" => $data["payment_method"]["billing_details"]["phone"],
            "country" => $data["payment_method"]["billing_details"]["address"]["country"],
            "city" => $data["payment_method"]["billing_details"]["address"]["city"],
            "state" => $data["payment_method"]["billing_details"]["address"]["state"],
            "zip" => $data["payment_method"]["billing_details"]["address"]["postal_code"],
            "address1" => $data["payment_method"]["billing_details"]["address"]["line1"],
            "address2" => $data["payment_method"]["billing_details"]["address"]["line2"], // Fixed typo
        ]);
    }

    private function extractCustomerName($fullName)
    {
        $nameParts = explode(' ', $fullName);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : null;
        return [$firstName, $lastName];
    }
}
