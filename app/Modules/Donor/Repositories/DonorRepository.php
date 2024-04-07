<?php

namespace App\Modules\Donor\Repositories;

use App\Models\Donor;
use App\Models\User;

class DonorRepository
{

    public function createDonor(string $email, string $stripeCustomerId, User|null $user = null)
    {
        return Donor::create([
            'user_id' => $user?->id,
            'email' => $email,
            'stripe_customer_id' => $stripeCustomerId
        ]);
    }

    public function getDonorByEmail(string $email)
    {
        return Donor::where('email', $email)->first();
    }

    public function getDonorByStripeCustomerId(string $stripeCustomerId)
    {
        return Donor::where('stripe_customer_id', $stripeCustomerId)->first();
    }
}
