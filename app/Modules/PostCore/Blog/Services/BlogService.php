<?php

namespace App\Modules\PostCore\Blog\Services;

use App\Enums\PostTypeEnum;
use App\Exceptions\ApiException;
use App\Models\Blog;
use App\Modules\Image\Services\ImageService;
use App\Modules\PostCore\Blog\Repositories\BlogRepository;
use App\Modules\PostCore\Post\Services\PostService;
use App\Modules\PostCore\PostCategory\Services\PostCategoryService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BlogService
{

    /**
     * @param BlogRepository $blogRepository
     */
    public function __construct(
        private readonly BlogRepository $blogRepository,
        private readonly PostService         $postService,
        private readonly ImageService        $imageService,
        private readonly PostCategoryService $postCategoryService
    )
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


    /**
     * Create Blogs From JSON File
     * This method creates the WordPress blogs (Old Blogs) that
     * had been created in JSON format you can find in this path "public/db"
     *
     * @return void
     * @throws \JsonException
     */
    public function createBlogsFromJsonFile(): void
    {
        $blogs = json_decode(file_get_contents(public_path('db/blogs.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach (array_reverse($blogs) as $blog) {
            // Save blog featured image in file storage and database
            $featuredImagePath = $this->imageService->saveImageByUrl($blog['featured_image']['src']);
            $featuredImageData = [
                'src' => $featuredImagePath,
                'alt_text' => $blog['featured_image']['alt_text']
            ];
            $featuredImage = $this->imageService->createImage($featuredImageData);

            // Create post for blog
            $postData = [
                'title' => $blog['title'],
                'excerpt' => $blog['excerpt'],
                'published' => 1,
                'meta_title' => $blog['meta_title'],
                'meta_keywords' => $blog['meta_keywords'],
                'meta_description' => $blog['meta_description'],
                'meta_robots' => $blog['meta_robots'],
                'meta_og_type' => $blog['meta_og_type']
            ];
            $post = $this->postService->createPost($postData);

            $post->created_at = \Carbon\Carbon::parse($blog['posted_date'])->toDateTimeString();
            $post->save();

            // Create blog
            $blogData = [
                'slug' => $blog['slug'],
                'location' => $blog['location'],
                'implementation_date' => \Carbon\Carbon::parse($blog['implementation_date'])->toDateTimeString(),
                'donation_form_id' => $blog['donation_form_id'],
                'featured_image_id' => $featuredImage->id,
            ];
            $post->blog()->create($blogData);

            // Create blog Contents
            $contents = [];
            foreach ($blog['contents'] as $index => $content) {
                $contentBody = $content['body'];

                if ($content['type'] === 'image') {
                    $imagePath = $this->imageService->saveImageByUrl($contentBody['src']);
                    $imageData = [
                        'src' => $imagePath,
                        'alt_text' => $contentBody['alt_text']
                    ];
                    $image = $this->imageService->createImage($imageData);

                    $contentBody = $image->id;
                }

                $contents[] = [
                    'type' => $content['type'],
                    'body' => $contentBody,
                    'order' => $index
                ];
            }
            $post->contents()->createMany($contents);

            // Create gallery
            $gallery = [];
            foreach ($blog['gallery'] as $image) {
                $imagePath = $this->imageService->saveImageByUrl($image['src']);
                $gallery[] = [
                    'src' => $imagePath,
                    'alt_text' => $image['alt_text']
                ];
            }
            $post->images()->createMany($gallery);

            // Get the categories of blog
            $categoriesSlug = array_map(function($category) {
                return $category['slug'];
            }, $blog['categories']);
            $categoriesSlug = $this->postCategoryService->getPostCategoriesBySlug($categoriesSlug, PostTypeEnum::BLOG);
            $post->categories()->attach($categoriesSlug?->pluck('id'));
        }
    }
}
