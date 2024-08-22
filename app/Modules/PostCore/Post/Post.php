<?php

namespace App\Modules\PostCore\Post;

use App\Modules\Image\Image;
use App\Modules\PostCore\Blog\Blog;
use App\Modules\PostCore\Career\Career;
use App\Modules\PostCore\PostCategory\PostCategory;
use App\Modules\PostCore\PostContent\PostContent;
use App\Modules\PostCore\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'published',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_robots',
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
        return $this->belongsToMany(Image::class, 'image_post');
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
