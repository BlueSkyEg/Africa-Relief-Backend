<?php

namespace App\Modules\PostCore\Post\Repositories;

use App\Models\Post;

class PostRepository
{

    /**
     * @param array $attributes
     * @return Post
     */
    public function create(array $attributes): Post
    {
        return Post::create($attributes);
    }
}
