<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Modules\Post\Project\Services\GetProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly GetProjectService $getProjectService)
    {
    }

    public function getPublishedProjects(Request $request): JsonResponse
    {
        return $this->getProjectService->getProjects(
            $request->query('categorySlug'),
            $request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'),
            true
        );
    }

    public function getPublishedProject(string $projectSlug): JsonResponse
    {
        return $this->getProjectService->getProject($projectSlug, true);
    }

    public function getRelatedProjects(string $projectSlug): JsonResponse
    {
        return $this->getProjectService->getRelatedProjects($projectSlug);
    }
}
