<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'period',
        'initial_amount',
        'recurring_amount',
        'stripe_subscription_id',
        'parent_payment_id',
        'donation_form_id',
        'created',
        'expiration',
        'status',
        'notes'
    ];
}
