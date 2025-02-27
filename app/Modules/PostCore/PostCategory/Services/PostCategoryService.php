<?php

namespace App\Modules\PostCore\PostCategory\Services;


use App\Enums\PostTypeEnum;
use App\Models\PostCategory;
use App\Modules\PostCore\PostCategory\Repositories\PostCategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class PostCategoryService
{
    public function __construct(private readonly PostCategoryRepository $postCategoryRepository)
    {
    }


    /**
     * @param PostTypeEnum $postType
     * @return Collection
     */
    public function getPostCategories(PostTypeEnum $postType): Collection
    {
        return $this->postCategoryRepository->getAll($postType);
    }


    /**
     * @param array $categoriesSlug
     * @param PostTypeEnum $postType
     * @return PostCategory|null
     */
    public function getPostCategoriesBySlug(array $categoriesSlug, PostTypeEnum $postType): ?Collection
    {
        return $this->postCategoryRepository->getAllByCategoriesSlug($categoriesSlug, $postType);
    }


    /**
     * @param array $attributes
     * @return PostCategory
     */
    public function createPostCategory(array $attributes): PostCategory
    {
        return $this->postCategoryRepository->create($attributes);
    }


    /**
     * @param PostTypeEnum $postType
     * @param string $fileName
     * @return void
     * @throws \JsonException
     */
    public function createPostCategoriesFromJsonFile(PostTypeEnum $postType, string $fileName): void
    {
        $categories = json_decode(file_get_contents(public_path("db/$fileName")), true, 512, JSON_THROW_ON_ERROR);

        foreach ($categories as $category) {
            $categoryData = [
                'post_type' => $postType,
                'name' => $category['name'],
                'slug' => $category['slug'],
                'meta_title' => $category['meta_title'],
                'meta_keywords' => $category['meta_keywords'],
                'meta_description' => $category['meta_description'],
                'meta_robots' => $category['meta_robots'],
                'meta_og_type' => $category['meta_og_type'],
                'order' => $category['order'] ?? null
            ];
            $this->createPostCategory($categoryData);
        }
    }
}
