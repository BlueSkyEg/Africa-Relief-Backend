<?php

namespace App\Http\Controllers;

use App\Modules\Post\Blog\Services\GetBlogService;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function __construct(private readonly GetBlogService $getBlogService)
    {
    }

    public function getBlogs(): JsonResponse
    {
        return $this->getBlogService->getBlogs();
    }

    public function getBlog(string $blogSlug): JsonResponse
    {
        return $this->getBlogService->getBlog($blogSlug);
    }

    public function getBlogsOfCategory(string $categorySlug): JsonResponse
    {
        return $this->getBlogService->getBlogsOfCategory($categorySlug);
    }
}
