<?php

namespace App\Modules\DonationCore\Subscription;

use App\Modules\DonationCore\Donation\Donation;
use App\Modules\DonationCore\DonationForm\DonationForm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'donation_form_id',
        'parent_donation_id',
        'stripe_subscription_id',
        'period',
        'initial_amount',
        'recurring_amount',
        'completed_date',
        'expiration_date',
        'status',
        'live_mode'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'expiration_at' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    public function donationForm(): BelongsTo
    {
        return $this->belongsTo(DonationForm::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function parentDonation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'parent_donation_id');
    }


    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? (in_array($value, ['active', 'canceled']) ? $value : 'incomplete') : null
        );
    }

    protected function completedDate(): Attribute
    {
        return Attribute::make(
            set: fn (string|int|null $value) => $value ? Carbon::parse($value)->toDateTimeString() : null
        );
    }

    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            set: fn (string|int|null $value) => $value ? Carbon::parse($value)->toDateTimeString() : null
        );
    }
}
