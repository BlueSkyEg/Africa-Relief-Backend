<?php

namespace App\Modules\DonationCore\Stripe\Services;

use Stripe\StripeClient;

class BaseStripeService
{

    public function __construct(protected StripeClient $stripe)
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }
}
