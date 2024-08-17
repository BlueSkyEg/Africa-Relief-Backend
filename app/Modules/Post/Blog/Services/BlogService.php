<?php

namespace App\Modules\Post\Blog\Services;

use App\Exceptions\ApiException;
use App\Modules\Post\Blog\Blog;
use App\Modules\Post\Blog\Repositories\BlogRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BlogService
{

    /**
     * @param BlogRepository $blogRepository
     */
    public function __construct(private readonly BlogRepository $blogRepository)
    {
    }


    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllBlogs(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->blogRepository->getAll($categorySlug, $perPage, $published);
    }


    /**
     * @param string $blogSlug
     * @param bool|null $published
     * @return Blog
     * @throws ApiException
     */
    public function getBlog(string $blogSlug, bool $published = null): Blog
    {
        $blog = $this->blogRepository->getBySlug($blogSlug, $published);

        if (!$blog) {
            throw new ApiException('Blog not found.');
        }

        return $blog;
    }


    /**
     * @param string $blogSlug
     * @return Collection
     * @throws ApiException
     */
    public function getRelatedBlogs(string $blogSlug): Collection
    {
        $currentBlog = $this->blogRepository->getBySlug($blogSlug, true);

        if (!$currentBlog) {
            throw new ApiException('Current blog not found.');
        }

        return $this->blogRepository->getRelated($currentBlog);
    }


    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBlogsGallery(int $perPage): LengthAwarePaginator
    {
        return $this->blogRepository->getGallery($perPage);
    }


    /**
     * @param string $searchTerm
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchBlogs(string $searchTerm, int $perPage): LengthAwarePaginator
    {
        return $this->blogRepository->search($searchTerm, $perPage);
    }
}
