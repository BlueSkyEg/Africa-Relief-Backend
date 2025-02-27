<?php

namespace App\Modules\DonationCore\Donor\Repositories;

use App\Models\Donor;

class DonorRepository
{
    /**
     * @param string $email
     * @return Donor|null
     */
    public function getByEmail(string $email): ?Donor
    {
        return Donor::where('email', $email)->first();
    }


    /**
     * @param string $stripeCustomerId
     * @return Donor|null
     */
    public function getByStripeCustomerId(string $stripeCustomerId): ?Donor
    {
        return Donor::where('stripe_customer_id', $stripeCustomerId)->first();
    }


    /**
     * @param array $attributes
     * @return Donor|null
     */
    public function create(array $attributes): ?Donor
    {
        return Donor::create($attributes);
    }
}
