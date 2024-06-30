<?php

namespace App\Modules\CarouselSlide\Repositories;

use App\Models\CarouselSlide;
use Illuminate\Database\Eloquent\Collection;

class CarouselSlideRepository
{
    public function getCarousel(string $carouselType): ?Collection
    {
        $carousel = CarouselSlide::where('carousel_type', $carouselType)->get();

        if ($carousel) {
            return $carousel;
        }

        return null;
    }
}
