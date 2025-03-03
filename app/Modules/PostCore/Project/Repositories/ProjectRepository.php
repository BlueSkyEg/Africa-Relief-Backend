<?php

namespace App\Modules\PostCore\Project\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{

    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAll(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Project::when($published === true, function (Builder $query) {
            return $query->whereRelation('post', 'published', '=', 1);
        })
            ->when($published === false, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 0);
            })
            ->when($categorySlug, function (Builder $query) use ($categorySlug) {
                return $query->whereHas('post.categories', function (Builder $query) use ($categorySlug) {
                    $query->where('post_type', '=', PostTypeEnum::PROJECT->value);
                    $query->where('slug', '=', $categorySlug);
                });
            })->whereHas('post', function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            })
            ->with('post.categories', 'featuredImage')
            ->orderBy('order', 'asc')  // Order by the 'order' column in ascending order
            ->paginate($perPage);
    }
    /**
     * neglect categories of crisis and back-to-school
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllLatest(int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Project::when($published === true, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 1);
            })
            ->when($published === false, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 0);
            })
            ->whereDoesntHave('post.categories', function (Builder $query) {
                $query->whereIn('slug', ['crisis', 'back-to-school']);
            })
            ->whereHas('post', function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            })
            ->with('post.categories', 'featuredImage')
            ->paginate($perPage);
    }

    /**
     * @param array $categorySlugs
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllByCategories(array $categorySlugs, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return Project::when($published === true, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 1);
            })
            ->when($published === false, function (Builder $query) {
                return $query->whereRelation('post', 'published', '=', 0);
            })
            ->when(!empty($categorySlugs), function (Builder $query) use ($categorySlugs) {
                return $query->whereHas('post.categories', function (Builder $query) use ($categorySlugs) {
                    $query->where('post_type', '=', PostTypeEnum::PROJECT->value);
                    $query->whereIn('slug', $categorySlugs); // Use 'whereIn' to filter by multiple slugs
                });
            })
            ->whereHas('post', function (Builder $query) {
                $query->orderBy('created_at', 'desc');
            })
            ->with('post.categories', 'featuredImage')
            ->orderBy('order', 'asc') // Order by the 'order' column in ascending order
            ->paginate($perPage);
    }


    /**
     * @param string $projectSlug
     * @param bool|null $published
     * @return Project|null
     */
    public function getBySlug(string $projectSlug, bool|null $published = null): ?Project
    {
        return Project::where('slug', $projectSlug)
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
     * Get related projects of categories and exclude current project
     * If number of projects less than 3 we will fetch latest projects
     *
     * @param Project $currentProject
     * @return Collection
     */
    public function getRelated(Project $currentProject): Collection
    {
        $relatedBlogsCount = $currentProject->post->categories->loadCount('posts')->pluck('posts_count')->first();

        return Project::whereNot('id', $currentProject->id)
            ->when($relatedBlogsCount > 3, function (Builder $query) use ($currentProject) {
                $query->whereHas('post.categories', function (Builder $query) use ($currentProject) {
                    $query->whereIn('slug', $currentProject->post->categories->pluck('slug')->toArray());
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
     * @param string $searchTerm
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $searchTerm, int $perPage): LengthAwarePaginator
    {
        return Project::whereHas('post', function ($query) use ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
            $query->where('published', '=', 1);
        })->paginate($perPage);
    }
}
