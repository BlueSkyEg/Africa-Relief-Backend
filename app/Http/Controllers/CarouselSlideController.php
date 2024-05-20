<?php

namespace App\Http\Controllers;

use App\Modules\CarouselSlide\Services\GetCarouselSlideService;
use Illuminate\Http\JsonResponse;

class CarouselSlideController extends Controller
{
    public function __construct(private readonly GetCarouselSlideService $getCarouselSlideService)
    {
    }

    public function getCarousel(string $carouselType): JsonResponse
    {
        return $this->getCarouselSlideService->getCarousel($carouselType);
    }
}
