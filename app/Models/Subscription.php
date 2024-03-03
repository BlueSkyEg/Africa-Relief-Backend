<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'donor_id',
        'period',
        'initial_amount',
        'recurring_amount',
        'stripe_subscription_id',
        'parent_payment_id',
        'donation_form_id',
        'created_at',
        'expiration_at',
        'status',
        'notes'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::createFromTimestamp($value)->format('Y-m-d H:i:s'),
        );
    }

    protected function expirationAt(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Carbon::createFromTimestamp($value)->format('Y-m-d H:i:s'),
        );
    }
}
