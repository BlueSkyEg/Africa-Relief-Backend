<?php

namespace App\Http\Controllers;

use App\Modules\CarouselSlide\Resources\CarouselSlideResource;
use App\Modules\CarouselSlide\Services\GetCarouselSlideService;
use Illuminate\Http\JsonResponse;

class CarouselSlideController extends Controller
{
    public function __construct(private readonly GetCarouselSlideService $getCarouselSlideService)
    {
    }

    public function getCarousel(string $carouselType): JsonResponse
    {
        $slides = $this->getCarouselSlideService->getCarousel($carouselType);

        return response()->success('Carousel retrieved successfully.', CarouselSlideResource::collection($slides));
    }
}
