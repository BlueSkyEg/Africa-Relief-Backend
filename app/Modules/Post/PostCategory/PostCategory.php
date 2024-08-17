<?php

namespace App\Modules\Post\PostCategory;

use App\Enums\PostTypeEnum;
use App\Modules\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PostCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_type',
        'name',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_robots',
        'meta_og_title',
        'meta_og_type'
    ];

    protected $casts = [
        'post_type' => PostTypeEnum::class
    ];

    // Post belongs to many categories
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post', 'category_id','post_id');
    }
}
