<?php

namespace App\Modules\Post\PostCategory\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Collection;

class PostCategoryRepository
{
    public function getBlogCategories(): Collection
    {
        return PostCategory::where('post_type', PostTypeEnum::BLOG->value)->get();
    }

    public function getProjectCategories(): Collection
    {
        return PostCategory::where('post_type', PostTypeEnum::PROJECT->value)->get();
    }
}
