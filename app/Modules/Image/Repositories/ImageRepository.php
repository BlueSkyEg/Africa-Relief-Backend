<?php

namespace App\Modules\Image\Repositories;

use App\Modules\Image\Image;

class ImageRepository
{

    /**
     * @param array $attributes
     * @return Image
     */
    public function create(array $attributes): Image
    {
        return Image::create($attributes);
    }
}
