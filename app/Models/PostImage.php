<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'src',
        'alt_text'
    ];
}
