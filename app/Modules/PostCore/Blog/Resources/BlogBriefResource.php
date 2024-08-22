<?php

namespace App\Modules\PostCore\Blog\Resources;

use App\Modules\PostCore\PostCategory\Resources\PostCategoryBriefResource;
use App\Modules\Image\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogBriefResource extends JsonResource
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
            'categories' => PostCategoryBriefResource::collection($this->post->categories),
            'featured_image' => new ImageResource($this->featuredImage),
            'created_at' => $this->post->created_at
        ];
    }
}
