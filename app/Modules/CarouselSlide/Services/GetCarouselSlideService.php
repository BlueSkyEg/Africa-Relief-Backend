<?php

namespace App\Modules\CarouselSlide\Services;

use App\Exceptions\ApiException;
use App\Modules\CarouselSlide\Repositories\CarouselSlideRepository;
use Illuminate\Database\Eloquent\Collection;

class GetCarouselSlideService
{
    public function __construct(private readonly CarouselSlideRepository $carouselSlideRepository)
    {
    }

    public function getCarousel(string $carouselType): Collection
    {
        try {
            $slides = $this->carouselSlideRepository->getCarousel($carouselType);

            if (!$slides) {
                throw new ApiException('Carousel not found.');
            }

            return $slides;
        } catch (\Exception $e) {
            throw new ApiException('An error occurred while retrieving the carousel: ' . $e->getMessage());
        }
    }
}
