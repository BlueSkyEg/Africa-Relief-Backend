<?php

namespace App\Modules\DonationForm\Repositories;

use App\Models\DonationForm;

class DonationFormRepository
{
    public function getDonationFormById(int $formId): DonationForm
    {
        return DonationForm::findOrFail($formId);
    }
}
