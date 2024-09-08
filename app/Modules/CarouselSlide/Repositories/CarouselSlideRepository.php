<?php

namespace App\Modules\CarouselSlide\Repositories;

use App\Modules\CarouselSlide\CarouselSlide;
use Illuminate\Database\Eloquent\Collection;

class CarouselSlideRepository
{
    public function getCarousel(string $carouselType): ?Collection
    {
        $carousel = CarouselSlide::where('carousel_type', $carouselType)
        ->where('is_active', 1) // Check for active slides
        ->get();

        if ($carousel) {
            return $carousel;
        }

        return null;
    }
}
