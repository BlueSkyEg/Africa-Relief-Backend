<?php

namespace App\Http\Controllers\V2\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\BlogBriefResource;
use App\Http\Resources\V2\BlogGalleryResource;
use App\Http\Resources\V2\BlogResource;
use App\Modules\PostCore\Blog\Services\BlogService;
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
            $request->query('perPage') ?: 9,
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

    public function getBlog(string $blogSlug): JsonResponse
    {
        $blog = $this->blogService->getBlog($blogSlug);

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
