<?php

namespace App\Modules\DonationCore\Donor\Exceptions;

use App\Exceptions\CustomException;

class DonorNotFoundException extends CustomException
{
    protected $message = "Donor not found.";
}
