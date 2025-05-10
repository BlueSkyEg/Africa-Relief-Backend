<?php

namespace App\Http\Controllers\V2\Post;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Resources\V2\ProjectBriefResource;
use App\Http\Resources\V2\ProjectResource;
use App\Modules\PostCore\Project\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService)
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublishedProjects(Request $request): JsonResponse
    {
        $projects = $this->projectService->getAllProjects(
            $request->query('categorySlug'),
            $request->query('perPage') ?: 9,
            true
        );

        return response()->pagination('Projects retrieved successfully.', ProjectBriefResource::collection($projects), $projects);
    }


    /**
     * @param string $projectSlug
     * @return JsonResponse
     * @throws ApiException
     */
    public function getPublishedProject(string $projectSlug): JsonResponse
    {
        $project = $this->projectService->getProject($projectSlug, true);

        return response()->success('Project retrieved successfully.', new ProjectResource($project));
    }

    public function getProject(string $projectSlug): JsonResponse
    {
        $project = $this->projectService->getProject($projectSlug);

        return response()->success('Project retrieved successfully.', new ProjectResource($project));
    }


    /**
     * @param string $projectSlug
     * @return JsonResponse
     * @throws ApiException
     */
    public function getRelatedProjects(string $projectSlug): JsonResponse
    {
        $relatedProjects = $this->projectService->getRelatedProjects($projectSlug);

        return response()->success('Related projects retrieved successfully', ProjectBriefResource::collection($relatedProjects));
    }


    /**
     * @param string $searchTerm
     * @param Request $request
     * @return JsonResponse
     */
    public function searchProjects(string $searchTerm, Request $request): JsonResponse
    {
        $blogs = $this->projectService->searchProjects($searchTerm, $request->query('perPage') ?: 9);

        return response()->pagination('Search result retrieved successfully.', ProjectBriefResource::collection($blogs), $blogs);
    }
}
