<?php

namespace App\Modules\Donation\Services;

use App\Modules\Donation\Repositories\DonationRepository;
use App\Modules\Donor\Services\GetDonorService;
use Stripe\Charge;

class CreateDonationService
{
    public function __construct(
        private readonly DonationRepository $donationRepository,
        private readonly GetDonorService $getDonorService
    )
    {
    }

    public function createDonation(Charge $chargeObj)
    {
        $donor = $this->getDonorService->getDonorByStripeCustomerId($chargeObj->customer);
        return $this->donationRepository->createDonation([
            'donor_id' => $donor->id,
            'stripe_source_id' => $chargeObj->payment_method,
            'stripe_transaction_id' => $chargeObj->payment_intent,
            'payment_amount' => $chargeObj->amount / 100, // This amount in dollar
            'donation_form_id' => $chargeObj->metadata['Donation Form Id'],
            'donor_billing_comment' => $chargeObj->metadata['Comment'],
            'anonymous_donation' => (int) $chargeObj->metadata['Anonymous Donation'],
            'donor_billing_name' => $chargeObj->billing_details->name,
            'donor_billing_phone' => $chargeObj->billing_details->phone,
            'donor_billing_email' => $chargeObj->billing_details->email,
            'donor_billing_country' => $chargeObj->billing_details->address->country,
            'donor_billing_city' => $chargeObj->billing_details->address->city,
            'donor_billing_state' => $chargeObj->billing_details->address->state,
            'donor_billing_address_1' => $chargeObj->billing_details->address->line1,
            'donor_billing_address_2' => $chargeObj->billing_details->address->line2,
            'donor_billing_zip' => $chargeObj->billing_details->address->postal_code,
            'payment_mode' => $chargeObj->livemode ? 'live' : 'test',
            'completed_date' => $chargeObj->created,
            'status' => $chargeObj->status,
            'payment_currency' => $chargeObj->currency
        ]);
    }

}
