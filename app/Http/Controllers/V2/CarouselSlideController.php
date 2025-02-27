<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\CarouselSlideResource;
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
