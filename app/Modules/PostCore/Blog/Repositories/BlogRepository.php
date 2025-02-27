<?php

namespace App\Modules\PostCore\Blog\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository
{

    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAll(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Blog::when($published === true, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->when($published === false, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 0);
            })
            ->when($categorySlug, function (Builder $query) use ($categorySlug) {
                return $query->whereHas('post.categories', function (Builder $query) use ($categorySlug) {
                    $query->where('post_type', '=', PostTypeEnum::BLOG->value);
                    $query->where('slug', '=', $categorySlug);
                });
            })->whereHas('post', function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            })
            ->with('post.categories', 'featuredImage')
            ->orderBy(Post::select('created_at')
            ->whereColumn('posts.id', 'blogs.post_id'), 'desc') // Ordering by post.created_at
            ->paginate($perPage);
    }


    /**
     * @param string $blogSlug
     * @param bool|null $published
     * @return Blog|null
     */
    public function getBySlug(string $blogSlug, bool|null $published = null): ?Blog
    {
        return Blog::where('slug', $blogSlug)
            ->when($published, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 1);
            })
            ->with([
                'post' => ['images', 'categories', 'contents'],
                'featuredImage',
                'donationForm'
            ])->first();
    }


    /**
     * Get related blogs of categories and exclude current blog
     * If number of blogs less than 3 we will fetch latest blogs
     *
     * @param Blog $currentBlog
     * @return Collection
     */
    public function getRelated(Blog $currentBlog): Collection
    {
        $relatedBlogsCount = $currentBlog->post->categories->loadCount('posts')->pluck('posts_count')->first();

        return Blog::whereNot('id', $currentBlog->id)
            ->when($relatedBlogsCount > 3, function (Builder $query) use ($currentBlog) {
                $query->whereHas('post.categories', function (Builder $query) use ($currentBlog) {
                    $query->whereIn('slug', $currentBlog->post->categories->pluck('slug')->toArray());
                });
            })
            ->whereHas('post', function (Builder $query) {
                $query->where('published', '=', 1);
                $query->orderBy('created_at', 'desc');
            })
            ->with('post.categories', 'featuredImage')
            ->limit(3)
            ->get();
    }


    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getGallery(int $perPage): LengthAwarePaginator
    {
        return Blog::whereHas('post', function (Builder $query) {
            $query->where('published', '=', 1);
            $query->orderBy('created_at', 'desc');
        })
            ->with('featuredImage')
            ->paginate($perPage);
    }


    /**
     * @param string $searchTerm
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $searchTerm, int $perPage): LengthAwarePaginator
    {
        return Blog::whereHas('post', function ($query) use ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
            $query->where('published', '=', 1);
        })->paginate($perPage);
    }
}
