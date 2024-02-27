<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'id',
        'donor_id',
        'donation_id',
        'stripe_subscription_id',
        'period',
        'initial_amount',
        'recurring_amount',
        'status',
        'notes',
        'created',
        'expiration',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'subscription_id');
    }
}
