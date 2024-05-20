<?php

namespace App\Modules\Post\Project\Repositories;

use App\Enums\PostTypeEnum;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProjectRepository
{
    public function getProjects(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
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
            ->paginate($perPage);
    }

    public function getProject(string $projectSlug, bool|null $published = null): Project|null
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


    /*
     * Get related projects of categories and exclude current project
     * If number of projects less than 3 we will fetch latest projects
     */
    public function getRelatedProjects(Project $currentProject)
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
}
