<?php

namespace App\Modules\Stripe\Services;

use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\User;

class BaseStripeService
{
    protected StripeClient $stripe;
    protected User $user;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
        $this->user = User::find(3);
    }
}
