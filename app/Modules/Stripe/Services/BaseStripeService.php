<?php

namespace App\Modules\Stripe\Services;

use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\User;

class BaseStripeService
{

    public function __construct(protected StripeClient $stripe)
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
}
