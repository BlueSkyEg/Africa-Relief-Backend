<?php

namespace App\Modules\Donation\Services;

use App\Modules\Donation\Repositories\DonationRepository;

class GetDonationService
{
    public function __construct(private DonationRepository $donationRepository)
    {
    }
}
