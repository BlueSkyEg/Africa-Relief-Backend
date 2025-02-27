<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'updated_at' => 'datetime:Y-m-d\TH:i:s\Z'
    ];
}
