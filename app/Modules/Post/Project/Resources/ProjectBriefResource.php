<?php

namespace App\Modules\Post\Project\Resources;

use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use App\Modules\Image\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBriefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'title' => $this->post->title,
            'slug' => $this->slug,
            'excerpt' => $this->post->excerpt,
            'published' => $this->post->published,
            'categories' => PostCategoryResource::collection($this->post->categories),
            'featured_image' => new ImageResource($this->featuredImage),
        ];
    }
}
