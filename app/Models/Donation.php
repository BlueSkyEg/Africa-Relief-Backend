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

    protected $fillable = [
        'donor_id',
        'subscription_id',
        'donation_form_id',
        'payment_method_id',
        'stripe_transaction_id',
        'amount',
        'currency',
        'billing_comment',
        'completed_date',
        'status',
        'anonymous_donation',
        'live_mode',
        'donor_ip',
        'cs_exchange_rate',
        'contribution',
    ];

    protected $casts = [
      'completed_date' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    // Relations
    public function donationForm(): BelongsTo
    {
        return $this->belongsTo(DonationForm::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Accessors and Mutators
    protected function completedDate(): Attribute
    {
        return Attribute::make(
            set: fn (string|int|null $value) => $value ? Carbon::parse($value)->toDateTimeString() : null
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? (in_array($value, ['succeeded', 'failed', 'refunded']) ? $value : 'incomplete') : null
        );
    }
}
