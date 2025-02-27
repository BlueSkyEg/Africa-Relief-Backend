<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'username',
        'phone',
        'address',
        'img',
        'active'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'updated_at' => 'datetime:Y-m-d\TH:i:s\Z'
    ];

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }

	public function donations(): HasManyThrough
	{
		return $this->hasManyThrough(Donation::class, Donor::class);
	}

	public function subscriptions(): HasManyThrough
	{
		return $this->hasManyThrough(Subscription::class, Donor::class);
	}

	public function payment_methods(): HasManyThrough
	{
		return $this->hasManyThrough(PaymentMethod::class, Donor::class);
	}

}
