<?php

namespace App\Modules\PostCore\Post\Services;

use App\Modules\PostCore\Post\Post;
use App\Modules\PostCore\Post\Repositories\PostRepository;

class PostService
{

    public function __construct(private readonly PostRepository $postRepository)
    {
    }


    /**
     * @param array $attributes
     * @return Post
     */
    public function createPost(array $attributes): Post
    {
        return $this->postRepository->create($attributes);
    }
}
