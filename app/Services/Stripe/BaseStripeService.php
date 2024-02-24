<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use Stripe\StripeClient;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseStripeService
{
    protected StripeClient $stripe;
    protected $user;
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
        $this->user = JWTAuth::user();
    }
}
