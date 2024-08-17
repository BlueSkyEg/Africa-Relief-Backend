<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use App\Modules\Post\PostCategory\Services\PostCategoryService;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends Controller
{
    public function __construct(private readonly PostCategoryService $getPostCategoryService)
    {
    }


    /**
     * @return JsonResponse
     */
    public function getBlogCategories(): JsonResponse
    {
        $categories = $this->getPostCategoryService->getBlogCategories();

        return response()->success('Blog categories retrieved successfully.', PostCategoryResource::collection($categories));
    }


    /**
     * @return JsonResponse
     */
    public function getProjectCategories(): JsonResponse
    {
        $categories = $this->getPostCategoryService->getProjectCategories();

        return response()->success('Project categories retrieved successfully', PostCategoryResource::collection($categories));
    }
}
