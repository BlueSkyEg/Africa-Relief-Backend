<?php

namespace App\Services;

use App\Models\Donation;

class DonationService
{
    public function store(array $data)
    {
        try {
            // Process the donation and store it in the database
            $donation = Donation::create($data);
            return ['message' => 'Donation processed successfully', 'donation' => $donation];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
