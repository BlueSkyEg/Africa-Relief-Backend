<?php

namespace App\Modules\Donation\Repositories;

use App\Models\Donation;
use App\Models\Donor;
use Stripe\Charge;
use Stripe\PaymentIntent;

class DonationRepository
{
    public function getDonationById(int $donationId)
    {
        return Donation::where('id', $donationId)->first();
    }

    public function getDonationByStripeTransactionId(string $stripeTransactionId)
    {
        return Donation::where('stripe_transaction_id', $stripeTransactionId)->first();
    }

	public function getUserDonations($user)
	{
        return $user->donations()->with('donationForm', 'donor')->get();
//        return $user->donations()->where('payment_mode', 'live')->with('donationForm', 'donor')->get();
	}

    public function createDonation(array $donationAttributes)
    {
        return Donation::create($donationAttributes);
    }

    public function updateDonationStatus(string $paymentIntentId, string $paymentIntentStatus)
    {
        $donation = Donation::where('stripe_transaction_id', $paymentIntentId)->first();
        $donation->status = $paymentIntentStatus;
        $donation->save();
        return $donation;
    }

    public function updateSubscriptionParentDonation(int $parentDonationId, int $subscriptionId, string $status)
    {
        $donation = $this->getDonationById($parentDonationId);
        $donation->subscription_id = $subscriptionId;
        $donation->status = $status;
        $donation->save();
        return $donation;
    }
}
