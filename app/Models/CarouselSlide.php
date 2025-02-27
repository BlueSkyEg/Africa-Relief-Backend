<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarouselSlide extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'destination_label',
        'destination_type',
        'destination_slug',
        'image_id',
        'carousel_type',
        'is_active'
    ];

    // Slide has one image
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
