<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'username',
        'phone',
        'address',
        'img'
    ];

    protected $hidden = [
        'id',
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function img(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value ? asset('storage/users/images/'.$value) : null,
        );
    }

	public function donations(): HasManyThrough
	{
		return $this->hasManyThrough(Donation::class, Donor::class);
	}

	public function subscriptions(): HasManyThrough
	{
		return $this->hasManyThrough(Subscription::class, Donor::class);
	}

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }
}
