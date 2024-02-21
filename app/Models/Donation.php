<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'subscription_id',
        'project_title',
        'amount',
        'stripe_transaction_id',
        'currency',
        'cs_exchange_rate',
        'first_name',
        'last_name',
        'email',
        'country',
        'city',
        'state',
        'zip',
        'address1',
        'address2',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'subscription_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
