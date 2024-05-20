<?php

namespace App\Http\Controllers;

use App\Modules\Post\Career\Services\GetCareerService;
use Illuminate\Http\JsonResponse;

class CareerController extends Controller
{
    public function __construct(private readonly GetCareerService $getCareerService)
    {
    }

    public function getCareers(): JsonResponse
    {
        return $this->getCareerService->getCareers();
    }

    public function getCareer(string $careerSlug): JsonResponse
    {
        return $this->getCareerService->getCareer($careerSlug);
    }
}
