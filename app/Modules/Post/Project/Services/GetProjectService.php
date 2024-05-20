<?php

namespace App\Modules\Post\Project\Services;

use App\Modules\Post\Project\Repositories\ProjectRepository;
use App\Modules\Post\Project\Resources\ProjectBriefResource;
use App\Modules\Post\Project\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;

class GetProjectService
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }

    public function getProjects(string|null $categorySlug, int $perPage, bool|null $published = null): JsonResponse
    {
        try {
            $projects = $this->projectRepository->getProjects($categorySlug, $perPage, $published);

            return response()->apiWithPagination(true, 'projects retrieved successfully', ProjectBriefResource::collection($projects->items()), $projects);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getProject(string $projectSlug, bool $published = null): JsonResponse
    {
        try {
            $project = $this->projectRepository->getProject($projectSlug, $published);

            if (!$project) {
                return response()->api(false, 'project not found');
            }

            return response()->api(true, 'project retrieved successfully', new ProjectResource($project));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getRelatedProjects(string $projectSlug): JsonResponse
    {
        try {
            $currentProject = $this->projectRepository->getProject($projectSlug, true);

            if (!$currentProject) {
                return response()->api(false, 'project not found');
            }

            $relatedProjects = $this->projectRepository->getRelatedProjects($currentProject);

            return response()->api(true, 'related projects retrieved successfully', ProjectBriefResource::collection($relatedProjects));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
