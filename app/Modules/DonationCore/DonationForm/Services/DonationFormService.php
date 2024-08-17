<?php

namespace App\Modules\DonationCore\DonationForm\Services;

use App\Exceptions\ApiException;
use App\Modules\DonationCore\DonationForm\DonationForm;
use App\Modules\DonationCore\DonationForm\Repositories\DonationFormRepository;

class DonationFormService
{
    public function __construct(private readonly DonationFormRepository $donationFormRepository)
    {
    }


    /**
     * @param int $formId
     * @return DonationForm
     * @throws ApiException
     */
    public function getDonationFormById(int $formId): DonationForm
    {
        $donationForm = $this->donationFormRepository->getDonationFormById($formId);

        if (!$donationForm) {
            throw new ApiException('Donation Form not found.');
        }

        return $donationForm;
    }
}
