<?php

namespace App\Services\Donation;

use App\Models\Donation;

class DonationService
{
    public function store( $data)
    {
        $donation = Donation::create([
            "project_title" => $data["project_title"],
            "amount" => $data["amount"],
            "currency" => $data["currency"],
            // "ip_address" => $data["ip_address"],
            // "payment_mode" => $data["payment_mode"],
            "payment_gateway" => "stripe",
            "payment_transaction_id" => $data["id"],
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"],
            "phone" => $data["phone"],
            "country" => $data["country"],
            "city" => $data["city"],
            "state" => $data["state"],
            "zip" => $data["zip"],
            "address1" => $data["address1"],
            "address2" => $data["address2"]
        ]);

        // try {
        //     // Process the donation and store it in the database
        //     ]);
        //     return ["message" => "Donation processed successfully", "donation" => $donation];
        // } catch (\Exception $e) {
        //     return ["error" => $e->getMessage()];
        // }
        
    }
}
