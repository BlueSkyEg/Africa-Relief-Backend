<?php

namespace App\Modules\DonationCore\DonationForm\Repositories;

use App\Modules\DonationCore\DonationForm\DonationForm;

class DonationFormRepository
{
    /**
     * @param int $formId
     * @return DonationForm|null
     */
    public function getDonationFormById(int $formId): ?DonationForm
    {
        return DonationForm::find($formId);
    }
}
