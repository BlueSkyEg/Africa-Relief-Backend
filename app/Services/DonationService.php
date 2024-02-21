<?php

namespace App\Services;

use App\Models\Donation;

class DonationService
{
    public function getDonationsByUserId($userId)
    {
        // Retrieve donations for the specified user
        return Donation::where('user_id', $userId)->get();
    }
}
