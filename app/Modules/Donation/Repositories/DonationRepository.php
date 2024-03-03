<?php

namespace App\Modules\Donation\Repositories;

use App\Models\Donation;
use App\Models\Donor;
use Carbon\Carbon;
use Stripe\PaymentIntent;

class DonationRepository
{
    public function createDonation(PaymentIntent $paymentIntent, Donor $donor, int $donationFormId, string $donorBillingComment, int $anonymousDonation)
    {
        return Donation::create([
            'donor_id' => $donor->id,
            'stripe_source_id' => $paymentIntent->payment_method->id,
            'stripe_transaction_id' => $paymentIntent->id,
            'payment_amount' => $paymentIntent->amount / 100, // This amount in dollar
            'donation_form_id' => $donationFormId,
            'donor_billing_comment' => $donorBillingComment,
            'anonymous_donation' => $anonymousDonation,
            'donor_billing_name' => $paymentIntent->payment_method->billing_details->name,
            'donor_billing_phone' => $paymentIntent->payment_method->billing_details->phone,
            'donor_billing_country' => $paymentIntent->payment_method->billing_details->address->country,
            'donor_billing_city' => $paymentIntent->payment_method->billing_details->address->city,
            'donor_billing_state' => $paymentIntent->payment_method->billing_details->address->state,
            'donor_billing_address_1' => $paymentIntent->payment_method->billing_details->address->line1,
            'donor_billing_address_2' => $paymentIntent->payment_method->billing_details->address->line2,
            'donor_billing_zip' => $paymentIntent->payment_method->billing_details->address->postal_code,
            'payment_mode' => $paymentIntent->livemode ? 'live' : 'test',
            'completed_date' => Carbon::createFromTimestamp($paymentIntent->created)->format('Y-m-d H:i:s'),
            'status' => $paymentIntent->status,
            'payment_currency' => $paymentIntent->currency
        ]);
    }

    public function updateDonationStatus(string $paymentIntentId, string $paymentIntentStatus)
    {
        return Donation::where('stripe_transaction_id', $paymentIntentId)
            ->update([
                'status' => $paymentIntentStatus
            ]);
    }
}
