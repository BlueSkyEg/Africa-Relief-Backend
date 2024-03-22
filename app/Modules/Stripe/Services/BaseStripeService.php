<?php

namespace App\Modules\Stripe\Services;

use Stripe\StripeClient;

class BaseStripeService
{

    public function __construct(protected StripeClient $stripe)
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
}
