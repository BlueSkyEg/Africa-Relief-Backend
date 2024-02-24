<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{

    protected $fillable = [
        'id',
        'user_id',
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

}
