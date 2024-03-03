<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'donor_id',
        'subscription_id',
        'donation_form_id',
        'stripe_source_id',
        'stripe_transaction_id',
        'payment_amount',
        'payment_currency',
        'donor_billing_phone',
        'donor_billing_country',
        'donor_billing_city',
        'donor_billing_state',
        'donor_billing_name',
        'donor_billing_address_1',
        'donor_billing_address_2',
        'donor_billing_zip',
        'donor_billing_comment',
        'completed_date',
        'status',
        'anonymous_donation',
        'payment_mode',
        'payment_donor_ip',
        'cs_exchange_rate'
    ];

    protected function completedDate(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::createFromTimestamp($value)->format('Y-m-d H:i:s'),
        );
    }
}
