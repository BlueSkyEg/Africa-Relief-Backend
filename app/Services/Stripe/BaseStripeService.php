<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use Stripe\StripeClient;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseStripeService
{
    protected StripeClient $stripe;
    protected ?string $stripeCustomerId;
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));

        // define stripe_customer_id
        $user = JWTAuth::user();
        $this->stripeCustomerId = $user ? $user->donor->stripe_customer_id : null;
    }
}
