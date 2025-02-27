<?php

namespace App\Modules\PostCore\PostCategory\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Collection;

class PostCategoryRepository
{

    /**
     * @param PostTypeEnum $postType
     * @return Collection
     */
    public function getAll(PostTypeEnum $postType): Collection
    {
        return PostCategory::where('post_type', $postType)
        ->orderBy('order', 'asc')
        ->get();
    }


    /**
     * @param array $categoriesSlug
     * @param PostTypeEnum $postType
     * @return PostCategory|null
     */
    public function getAllByCategoriesSlug(array $categoriesSlug, PostTypeEnum $postType): ?Collection
    {
        return PostCategory::whereIn('slug', $categoriesSlug)->where('post_type', $postType)->get();
    }


    /**
     * @param array $attributes
     * @return PostCategory
     */
    public function create(array $attributes): PostCategory
    {
        return PostCategory::create($attributes);
    }
}
