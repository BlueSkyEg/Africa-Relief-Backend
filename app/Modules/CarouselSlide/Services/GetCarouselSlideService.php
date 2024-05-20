<?php

namespace App\Modules\CarouselSlide\Services;

use App\Modules\CarouselSlide\Repositories\CarouselSlideRepository;
use App\Modules\CarouselSlide\Resources\CarouselSlideResource;
use Illuminate\Http\JsonResponse;

class GetCarouselSlideService
{
    public function __construct(private readonly CarouselSlideRepository $carouselSlideRepository)
    {
    }

    public function getCarousel(string $carouselType): JsonResponse
    {
        try {
            $slides = $this->carouselSlideRepository->getCarousel($carouselType);

            if (empty($slides->toArray())) {
                return response()->api(false, 'carousel not found');
            }

            return response()->api(true, 'carousel retrieved successfully', CarouselSlideResource::collection($slides));
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
