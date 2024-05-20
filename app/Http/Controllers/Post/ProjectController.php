<?php

namespace App\Http\Controllers;

use App\Modules\Post\Project\Services\GetProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(private readonly GetProjectService $getProjectService)
    {
    }

    public function getProjects()
    {
        return $this->getProjectService->getProjects();
    }

    public function getProject(string $blogSlug): JsonResponse
    {
        return $this->getProjectService->getProject($blogSlug);
    }

    public function getProjectsOfCategory(string $categorySlug): JsonResponse
    {
        return $this->getProjectService->getProjectsOfCategory($categorySlug);
    }
}
