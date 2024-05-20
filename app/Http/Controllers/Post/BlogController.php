<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Modules\Post\Blog\Services\GetBlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly GetBlogService $getBlogService)
    {
    }

    public function getPublishedBlogs(Request $request): JsonResponse
    {
        return $this->getBlogService->getBlogs(
            $request->query('categorySlug'),
            $request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'),
            true
        );
    }

    public function getPublishedBlog(string $blogSlug): JsonResponse
    {
        return $this->getBlogService->getBlog($blogSlug, true);
    }

    public function getRelatedBlogs(string $blogSlug): JsonResponse
    {
        return $this->getBlogService->getRelatedBlogs($blogSlug);
    }

    public function getBlogsGallery(Request $request): JsonResponse
    {
        return $this->getBlogService->getBlogsGallery($request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'));
    }
}
