<?php

namespace App\Modules\Post\PostCategory\Services;


use App\Modules\Post\PostCategory\Repositories\PostCategoryRepository;
use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class PostCategoryService
{
    public function __construct(private readonly PostCategoryRepository $postCategoryRepository)
    {
    }


    /**
     * @return Collection
     */
    public function getBlogCategories(): Collection
    {
        return $this->postCategoryRepository->getBlogCategories();
    }


    /**
     * @return Collection
     */
    public function getProjectCategories(): Collection
    {
        return $this->postCategoryRepository->getProjectCategories();
    }
}
