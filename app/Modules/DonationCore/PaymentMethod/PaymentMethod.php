<?php

namespace App\Modules\DonationCore\PaymentMethod;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'stripe_payment_method_id',
        'fingerprint_id',
        'exp_month',
        'exp_year',
        'last4',
        'brand',
        'wallet',
        'name',
        'email',
        'phone',
        'country',
        'city',
        'state',
        'street_address',
        'postal_code',
        'setup_for_future_use',
        'default'
    ];
}
