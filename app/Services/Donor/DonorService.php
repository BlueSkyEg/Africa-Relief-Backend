<?php

namespace App\Services\Donor;

use App\Models\Donor;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\Stripe\Customer\CustomerService;

class DonorService
{
    public function __construct(public CustomerService $customerService)
    {
    }
    public function registerNewDonor($user)
    {
        Donor::create([
            "user_id"             => $user->id,
            "email"               => $user->email,
            "stripe_customer_id"  => $this->customerService->create($user),
        ]);
    }
}
