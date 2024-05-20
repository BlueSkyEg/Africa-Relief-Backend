<?php

namespace App\Modules\Post\Blog\Resources;

use App\Modules\Image\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogGalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->post->title,
            'slug' => $this->slug,
            'featured_image' => new ImageResource($this->featuredImage)
        ];
    }
}
