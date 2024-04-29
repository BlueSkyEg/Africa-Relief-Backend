<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'donor_billing_name',
        'donor_billing_email',
        'donor_billing_phone',
        'donor_billing_country',
        'donor_billing_city',
        'donor_billing_state',
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

    protected $hidden = [
        'donor_id',
        'donation_form_id',
        'laravel_through_key'
    ];

    protected $casts = [
      'completed_date' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    // Relations
    public function donationForm(): BelongsTo
    {
        return $this->belongsTo(DonationForm::class)->select(['id', 'title']);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class)->select(['id', 'email']);
    }

    // Accessors and Mutators
    protected function completedDate(): Attribute
    {
        return Attribute::make(
            set: fn (string|null $value) => $value ? Carbon::parse($value)->toDateTimeString() : null
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? (in_array($value, ['succeeded', 'failed', 'refunded']) ? $value : 'incomplete') : null
        );
    }
}
