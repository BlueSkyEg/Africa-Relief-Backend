<?php

namespace App\Modules\Donor\Repositories;

use App\Models\Donor;
use App\Models\User;
use App\Modules\Stripe\Services\Customer\CreateCustomerService;

class DonorRepository
{
    public function __construct(public CreateCustomerService $createCustomerService)
    {
    }

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
}
