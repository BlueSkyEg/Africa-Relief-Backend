<?php

namespace App\Modules\Donor\Repositories;

use App\Models\Donor;

class DonorRepository
{
    public function getDonorByEmail(string $email)
    {
        return Donor::where('email', $email)->first();
    }
}
