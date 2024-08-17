<?php

namespace App\Modules\Post\Project\Services;

use App\Exceptions\ApiException;
use App\Modules\Post\Project\Project;
use App\Modules\Post\Project\Repositories\ProjectRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }


    /**
     * @param string|null $categorySlug
     * @param int $perPage
     * @param bool|null $published
     * @return LengthAwarePaginator
     */
    public function getAllProjects(string|null $categorySlug, int $perPage, bool|null $published = null): LengthAwarePaginator
    {
        return $this->projectRepository->getAll($categorySlug, $perPage, $published);
    }


    /**
     * @param string $projectSlug
     * @param bool|null $published
     * @return Project
     * @throws ApiException
     */
    public function getProject(string $projectSlug, bool $published = null): Project
    {
        $project = $this->projectRepository->getBySlug($projectSlug, $published);

        if (!$project) {
            throw new ApiException('Project not found.');
        }

        return $project;
    }


    /**
     * @param string $projectSlug
     * @return Collection
     * @throws ApiException
     */
    public function getRelatedProjects(string $projectSlug): Collection
    {
            $currentProject = $this->projectRepository->getBySlug($projectSlug, true);

            if (!$currentProject) {
                throw new ApiException('Project not found.');
            }

            return $this->projectRepository->getRelated($currentProject);
    }


    /**
     * @param string $searchTerm
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchProjects(string $searchTerm, int $perPage): LengthAwarePaginator
    {
        return $this->projectRepository->search($searchTerm, $perPage);
    }
}
