<?php

namespace App\Modules\Image\Services;

use App\Models\Image;
use App\Modules\Image\Repositories\ImageRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function __construct(private readonly ImageRepository $imageRepository)
    {
    }


    /**
     * @param array $attributes
     * @return Image
     */
    public function createImage(array $attributes): Image
    {
        return $this->imageRepository->create($attributes);
    }


    /**
     * Save Image By Url
     * This method get image by url and save it in File Storage.
     *
     * @param string $url
     * @return string
     */
    public function saveImageByUrl(string $url): string
    {
        $imagePath = date('Y/') . date('m/') . Str::afterLast($url, '/');
        $imageContents = file_get_contents($url);
        Storage::put("images/$imagePath", $imageContents);

        return $imagePath;
    }
}
