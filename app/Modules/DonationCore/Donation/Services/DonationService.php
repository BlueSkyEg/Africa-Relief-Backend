<?php

namespace App\Modules\DonationCore\Donation\Services;

use App\Models\Donation;
use App\Modules\DonationCore\Donation\Repositories\DonationRepository;

class DonationService
{
    public function __construct(private readonly DonationRepository $donationRepository)
    {
    }

    /**
     * @param $id
     * @return Donation|null
     */
    public function getDonationById($id): ?Donation
    {
        return $this->donationRepository->getById($id);
    }


    /**
     * @param string $stripeTransactionId
     * @return Donation|null
     */
    public function getDonationByStripeTransactionId(string $stripeTransactionId): ?Donation
    {
        return $this->donationRepository->getByStripeTransactionId($stripeTransactionId);
    }


    /**
     * @param array $attributes
     * @return Donation
     */
    public function createDonation(array $attributes): Donation
    {
        return $this->donationRepository->create($attributes);
    }


    /**
     * @param array $attributes
     * @return Donation
     */
    public function updateOrCreateDonation(array $attributes): Donation
    {
        return $this->donationRepository->updateOrCreate($attributes);
    }
}
