<?php

namespace App\Modules\Post\PostCategory\Repositories;

use App\Enums\PostTypeEnum;
use App\Modules\Post\PostCategory\PostCategory;
use Illuminate\Database\Eloquent\Collection;

class PostCategoryRepository
{

    /**
     * @return Collection
     */
    public function getBlogCategories(): Collection
    {
        return PostCategory::where('post_type', PostTypeEnum::BLOG->value)->get();
    }


    /**
     * @return Collection
     */
    public function getProjectCategories(): Collection
    {
        return PostCategory::where('post_type', PostTypeEnum::PROJECT->value)->get();
    }
}
