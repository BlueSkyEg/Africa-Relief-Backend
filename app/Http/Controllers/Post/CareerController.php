<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Modules\Post\Career\Services\GetCareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function __construct(private readonly GetCareerService $getCareerService)
    {
    }

    public function getPublishedCareers(Request $request): JsonResponse
    {
        return $this->getCareerService->getCareers(
            $request->query('perPage') ?: env('DEFAULT_PAGINATION_PER_PAGE'),
            true
        );
    }

    public function getPublishedCareer(string $careerSlug): JsonResponse
    {
        return $this->getCareerService->getCareer($careerSlug, true);
    }
}
