<?php

namespace App\Modules\Donation\Services;

use App\Models\Donor;
use App\Modules\Donation\Repositories\DonationRepository;
use Stripe\PaymentIntent;

class CreateDonationService
{
    public function __construct(private DonationRepository $donationRepository)
    {
    }

    public function createDonation(PaymentIntent $paymentIntent, Donor $donor, int $donationFormId, string|null $billingComment, int $anonymousDonation)
    {
        return $this->donationRepository->createDonation($paymentIntent, $donor, $donationFormId, $billingComment, $anonymousDonation);
    }

}
