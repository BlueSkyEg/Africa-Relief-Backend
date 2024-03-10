<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationForm extends Model
{
    protected $fillable = [
        'title',
        'status',
        'fully_fund_level',
        'levels'
    ];

    use HasFactory;
}
