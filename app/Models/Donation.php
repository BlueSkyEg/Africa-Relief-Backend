<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{

    protected $fillable = [
        'id',
        'donor_id',
        'subscription_id',
        'project_title',
        'amount',
        'currency',
        'ip_address',
        'payment_mode',
        'payment_gateway',
        'payment_transaction_id',
        'first_name',
        'last_name',
        'phone',
        'country',
        'city',
        'state',
        'zip',
        'address1',
        'address2',
        'comment',
        'completed_date',
        'anonymous_donation',
        'cs_exchange_rate',
    ];

    protected $casts = [
        'id' => 'integer',
        'donor_id' => 'integer',
        'subscription_id' => 'integer',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
