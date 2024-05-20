<?php

namespace App\Modules\CarouselSlide\Resources;

use App\Modules\Image\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarouselSlideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'destination' => [
                'label' => $this->destination_label,
                'type' => $this->destination_type,
                'slug' => $this->destination_slug,
            ],
            'image' => new ImageResource($this->image),
        ];
    }
}
