<?php

namespace App\Modules\PostCore\Career\Resources;

use App\Modules\PostCore\PostContent\Resources\PostContentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
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
            'contents' => PostContentResource::collection($this->post->contents),
            'meta_data' => [
                'meta_title' => $this->post->meta_title,
                'meta_keywords' => $this->post->meta_keywords,
                'meta_description' => $this->post->meta_description,
                'meta_robots' => $this->post->meta_robots,
                'meta_og_type' => $this->post->meta_og_type,
            ],
            'created_at' => $this->post->created_at,
            'updated_at' => $this->post->updated_at
        ];
    }
}
