<?php

namespace App\Modules\DonationCore\Donation\Repositories;

use App\Modules\DonationCore\Donation\Donation;

class DonationRepository
{
    /**
     * @param $id
     * @return Donation|null
     */
    public function getById($id): ?Donation
    {
        return Donation::find($id);
    }


    /**
     * @param string $stripeTransactionId
     * @return Donation|null
     */
    public function getByStripeTransactionId(string $stripeTransactionId): ?Donation
    {
        return Donation::where('stripe_transaction_id', $stripeTransactionId)->first();
    }

    /**
     * @param array $attributes
     * @return Donation
     */
    public function create(array $attributes): Donation
    {
        return Donation::create($attributes);
    }

    /**
     * @param array $attributes
     * @return Donation
     */
    public function updateOrCreate(array $attributes): Donation
    {
        return Donation::updateOrCreate(...$attributes);
    }

	public function getUserDonations($user)
	{
        return $user->donations()->where('live_mode', env('STRIPE_LIVE_MODE'))->with('donationForm', 'donor', 'paymentMethod')->get();
	}
}
