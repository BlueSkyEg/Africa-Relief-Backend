<?php

namespace App\Modules\DonationCore\Stripe\Exceptions;

use App\Exceptions\CustomException;

class StripeApiException extends CustomException
{
    protected $message = "Payment failed.";
}
