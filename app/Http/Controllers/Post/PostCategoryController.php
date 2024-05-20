<?php

namespace App\Http\Controllers;

use App\Modules\Post\PostCategory\Services\GetPostCategoryService;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends Controller
{
    public function __construct(private readonly GetPostCategoryService $getPostCategoryService)
    {
    }

    public function getBlogCategories(): JsonResponse
    {
        return $this->getPostCategoryService->getBlogCategories();
    }

    public function getProjectCategories(): JsonResponse
    {
        return $this->getPostCategoryService->getProjectCategories();
    }
}
