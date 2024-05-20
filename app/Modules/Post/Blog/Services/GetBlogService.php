<?php

namespace App\Modules\Post\Blog\Services;

use App\Modules\Post\Blog\Repositories\BlogRepository;
use App\Modules\Post\Blog\Resources\BlogBriefResource;
use App\Modules\Post\Blog\Resources\BlogGalleryResource;
use App\Modules\Post\Blog\Resources\BlogResource;
use Illuminate\Http\JsonResponse;

class GetBlogService
{
    public function __construct(private readonly BlogRepository $blogRepository)
    {
    }

    public function getBlogs(string|null $categorySlug, int $perPage, bool|null $published = null): JsonResponse
    {
        try {
            $blogs = $this->blogRepository->getBlogs($categorySlug, $perPage, $published);

            return response()->apiWithPagination(true, 'blogs retrieved successfully', BlogBriefResource::collection($blogs->items()), $blogs);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getBlog(string $blogSlug, bool $published = null): JsonResponse
    {
        try {
            $blog = $this->blogRepository->getBlog($blogSlug, $published);

            if (!$blog) {
                return response()->api(false, 'blog not found');
            }

            return response()->api(true, 'blog retrieved successfully', new BlogResource($blog));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getRelatedBlogs(string $blogSlug): JsonResponse
    {
        try {
            $currentBlog = $this->blogRepository->getBlog($blogSlug, true);

            if (!$currentBlog) {
                return response()->api(false, 'blog not found');
            }

            $relatedBlogs = $this->blogRepository->getRelatedBlogs($currentBlog);

            return response()->api(true, 'related blogs retrieved successfully', BlogBriefResource::collection($relatedBlogs));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getBlogsGallery(int $perPage): JsonResponse
    {
        try {
            $gallery = $this->blogRepository->getBlogsGallery($perPage);

            return response()->apiWithPagination(true, 'gallery retrieved successfully', BlogGalleryResource::collection($gallery->items()), $gallery);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
