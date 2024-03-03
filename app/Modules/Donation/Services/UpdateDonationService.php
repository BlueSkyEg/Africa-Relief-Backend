<?php

namespace App\Modules\Donation\Services;

use App\Modules\Donation\Repositories\DonationRepository;

class UpdateDonationService
{
    public function __construct(private DonationRepository $donationRepository)
    {
    }

    public function updateDonationStatus(string $paymentIntentId, string $status)
    {
        return $this->donationRepository->updateDonationStatus($paymentIntentId, $status);
    }
}
