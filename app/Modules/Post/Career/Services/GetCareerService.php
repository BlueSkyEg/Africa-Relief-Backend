<?php

namespace App\Modules\Post\Career\Services;

use App\Modules\Post\Career\Repositories\CareerRepository;
use App\Modules\Post\Career\Resources\CareerBriefResource;
use App\Modules\Post\Career\Resources\CareerResource;
use Illuminate\Http\JsonResponse;

class GetCareerService
{
    public function __construct(private readonly CareerRepository $careerRepository)
    {
    }

    public function getCareers(int $perPage, bool|null $published = null): JsonResponse
    {
        try {
            $careers = $this->careerRepository->getCareers($perPage, $published);

            return response()->apiWithPagination(true, 'careers retrieved successfully', CareerBriefResource::collection($careers->items()), $careers);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    public function getCareer(string $careerSlug, bool|null $published = null): JsonResponse
    {
        try {
            $career = $this->careerRepository->getCareer($careerSlug, $published);

            if (!$career) {
                return response()->api(false, 'career not found');
            }

            return response()->api(true, 'career retrieved successfully', new CareerResource($career));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
