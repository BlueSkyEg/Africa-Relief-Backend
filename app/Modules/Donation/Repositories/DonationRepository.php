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
            //'subscription_id' => $paymentIntent->object,
            'stripe_source_id' => $paymentIntent->payment_method,
            'stripe_transaction_id' => $paymentIntent->id,
            'payment_total' => $paymentIntent->amount / 100, // This amount in dollar
            'donation_form_id' => $donationFormId,
            'donor_billing_name' => $paymentIntent->billing_details->name,
            'donor_billing_comment' => $donorBillingComment,
            'anonymous_donation' => $anonymousDonation,
            'donor_billing_phone' => $paymentIntent->billing_details->phone,
            'donor_billing_country' => $paymentIntent->billing_details->address->country,
            'donor_billing_city' => $paymentIntent->billing_details->address->city,
            'donor_billing_state' => $paymentIntent->billing_details->address->state,
            'donor_billing_address_1' => $paymentIntent->billing_details->address->line1,
            'donor_billing_address_2' => $paymentIntent->billing_details->address->line2,
            'donor_billing_zip' => $paymentIntent->billing_details->address->postal_code,
            'payment_mode' => $paymentIntent->livemode ? 'live' : 'test',
            'completed_date' => Carbon::createFromTimestamp($paymentIntent->created)->format('Y-m-d H:i:s'),
            'payment_currency' => $paymentIntent->currency,
            //'payment_donor_ip' => $paymentIntent->review['ip_address']
        ]);
    }
}
