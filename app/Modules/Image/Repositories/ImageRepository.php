<?php

namespace App\Modules\Image\Repositories;

use App\Models\Image;

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
