<?php

namespace App\Modules\Stripe\Repositories;

use App\Models\Donation;
use Carbon\Carbon;
use Stripe\PaymentIntent;

class DonationRepository
{
    public function store(PaymentIntent $paymentIntent)
    {
        Donation::create([
            'donor_id' => $paymentIntent->metadata->donor_id,
            'stripe_source_id' => $paymentIntent->payment_method,
            'stripe_transaction_id' => $paymentIntent->id,
            'payment_total' => $paymentIntent->object,
            'project_id' => $paymentIntent->metadata->project_id,
            'payment_currency' => $paymentIntent->currency,
            'donor_billing_phone' => $paymentIntent->metadata->phone,
            'donor_billing_country' => $paymentIntent->metadata->address_country,
            'donor_billing_city' => $paymentIntent->metadata->address_city,
            'donor_billing_state' => $paymentIntent->metadata->address_state,
            'donor_billing_first_name' => $paymentIntent->metadata->,
            'donor_billing_last_name' => $paymentIntent->metadata->,
            'donor_billing_address_1' => $paymentIntent->metadata->,
            'donor_billing_address_2' => $paymentIntent->metadata->,
            'donor_billing_zip' => $paymentIntent->metadata->,
            'donor_billing_comment' => $paymentIntent->metadata->,
            'completed_date' => Carbon::createFromTimestamp($paymentIntent->created)->format('Y-m-d H:i:s'),
            'anonymous_donation' => $paymentIntent->object,
            'payment_mode' => $paymentIntent->livemode ? 'live' : 'test',
            'payment_donor_ip' => $paymentIntent->object
        ]);
    }
}
