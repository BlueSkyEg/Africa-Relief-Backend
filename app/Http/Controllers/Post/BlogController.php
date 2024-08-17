<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Modules\Post\Blog\Resources\BlogBriefResource;
use App\Modules\Post\Blog\Resources\BlogGalleryResource;
use App\Modules\Post\Blog\Resources\BlogResource;
use App\Modules\Post\Blog\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublishedBlogs(Request $request): JsonResponse
    {
        $blogs = $this->blogService->getAllBlogs(
            $request->query('categorySlug'),
            $request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'),
            true
        );

        return response()->pagination('Blogs retrieved successfully', BlogBriefResource::collection($blogs), $blogs);
    }


    /**
     * @param string $blogSlug
     * @return JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function getPublishedBlog(string $blogSlug): JsonResponse
    {
        $blog = $this->blogService->getBlog($blogSlug, true);

        return response()->success('Blog retrieved successfully.', new BlogResource($blog));
    }


    /**
     * @param string $blogSlug
     * @return JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function getRelatedBlogs(string $blogSlug): JsonResponse
    {
        $relatedBlogs = $this->blogService->getRelatedBlogs($blogSlug);

        return response()->success('Related blogs retrieved successfully.', BlogBriefResource::collection($relatedBlogs));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBlogsGallery(Request $request): JsonResponse
    {
        $gallery = $this->blogService->getBlogsGallery($request->query('perPage') ?: 9);

        return response()->pagination('Gallery retrieved successfully', BlogGalleryResource::collection($gallery), $gallery);
    }


    /**
     * @param string $searchTerm
     * @param Request $request
     * @return JsonResponse
     */
    public function searchBlogs(string $searchTerm, Request $request): JsonResponse
    {
        $blogs = $this->blogService->searchBlogs($searchTerm, $request->query('perPage') ?: 9);

        return response()->pagination('Search result retrieved successfully.', BlogBriefResource::collection($blogs), $blogs);
    }
}
