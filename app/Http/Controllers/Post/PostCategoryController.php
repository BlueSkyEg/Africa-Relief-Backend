<?php

namespace App\Http\Controllers\Post;

use App\Enums\PostTypeEnum;
use App\Http\Controllers\Controller;
use App\Modules\PostCore\PostCategory\Resources\PostCategoryResource;
use App\Modules\PostCore\PostCategory\Services\PostCategoryService;
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
        $categories = $this->getPostCategoryService->getPostCategories(PostTypeEnum::BLOG);

        return response()->success('Blog categories retrieved successfully.', PostCategoryResource::collection($categories));
    }


    /**
     * @return JsonResponse
     */
    public function getProjectCategories(): JsonResponse
    {
        $categories = $this->getPostCategoryService->getPostCategories(PostTypeEnum::PROJECT);

        return response()->success('Project categories retrieved successfully', PostCategoryResource::collection($categories));
    }
}
