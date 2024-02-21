<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'period',
        'frequency',
        'amount',
        'fee_amount',
        'status',
        'notes',
        'start_date',
        'end_date',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'subscription_id');
    }
}
