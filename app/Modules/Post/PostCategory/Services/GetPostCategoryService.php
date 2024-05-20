<?php

namespace App\Modules\Post\PostCategory\Services;


use App\Modules\Post\PostCategory\Repositories\PostCategoryRepository;
use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use Illuminate\Http\JsonResponse;

class GetPostCategoryService
{
    public function __construct(private readonly PostCategoryRepository $postCategoryRepository)
    {
    }

    public function getBlogCategories(): JsonResponse
    {
        $categories = $this->postCategoryRepository->getBlogCategories();

        return response()->api(true, 'categories retrieved successfully', PostCategoryResource::collection($categories));
    }

    public function getProjectCategories(): JsonResponse
    {
        $categories = $this->postCategoryRepository->getProjectCategories();

        return response()->api(true, 'categories retrieved successfully', PostCategoryResource::collection($categories));
    }
}
