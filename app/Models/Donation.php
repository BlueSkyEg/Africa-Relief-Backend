<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'donor_id',
        'subscription_id',
        'stripe_source_id',
        'stripe_transaction_id',
        'payment_total',
        'project_id',
        'payment_currency',
        'donor_billing_phone',
        'donor_billing_country',
        'donor_billing_city',
        'donor_billing_state',
        'donor_billing_first_name',
        'donor_billing_last_name',
        'donor_billing_address_1',
        'donor_billing_address_2',
        'donor_billing_zip',
        'donor_billing_comment',
        'completed_date',
        'anonymous_donation',
        'payment_mode',
        'payment_donor_ip',
        'cs_exchange_rate'
    ];
}
