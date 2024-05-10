<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_robots',
        'meta_og_title',
        'meta_og_type'
    ];

    protected $with = ['categories'];

    // Category belongs to many posts
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PostCategory::class, 'category_post', 'post_id', 'category_id');
    }

    // Image belongs to many posts
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(PostImage::class, 'image_post', 'post_id', 'image_id');
    }

    // Post has many contents
    public function contents(): HasMany
    {
        return $this->hasMany(PostContent::class);
    }

    // Post has one blog
    public function blog(): HasOne
    {
        return $this->hasOne(Blog::class);
    }

    // Post has one project
    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    // Post has one career
    public function career(): HasOne
    {
        return $this->hasOne(Career::class);
    }
}
