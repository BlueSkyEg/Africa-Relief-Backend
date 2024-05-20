<?php

namespace App\Modules\Post\Blog\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository
{
    public function getBlogs(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
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
            ->paginate($perPage);
    }

    public function getBlog(string $blogSlug, bool|null $published = null): Blog|null
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

    /*
     * Get related blogs of categories and exclude current blog
     * If number of blogs less than 3 we will fetch latest blogs
     */
    public function getRelatedBlogs(Blog $currentBlog): Collection
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

    public function getBlogsGallery(int $perPage): LengthAwarePaginator
    {
        return Blog::whereHas('post', function (Builder $query) {
            $query->where('published', '=', 1);
            $query->orderBy('created_at', 'desc');
        })
            ->with('featuredImage')
            ->paginate($perPage);
    }
}
