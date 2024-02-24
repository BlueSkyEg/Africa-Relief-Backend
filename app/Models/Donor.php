<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'email',
        'stripe_customer_id',
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
    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'donor_id');
    }
}
