<?php

namespace App\Services\Donor;

use App\Models\Donor;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\Stripe\Customer\CreateCustomerService;

class DonorService
{
    public function __construct(public CreateCustomerService $createCustomerService)
    {
    }
    public function registerNewDonor($user)
    {
        Donor::create([
            "user_id"             => $user->id,
            "email"               => $user->email,
            "stripe_customer_id"  => $this->createCustomerService->create($user),
        ]);
    }
}
