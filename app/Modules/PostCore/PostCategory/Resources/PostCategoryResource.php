<?php

namespace App\Modules\PostCore\PostCategory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'meta_data' => [
                'meta_title' => $this->meta_title,
                'meta_keywords' => $this->meta_keywords,
                'meta_description' => $this->meta_description,
                'meta_robots' => $this->meta_robots,
                'meta_og_type' => $this->meta_og_type
            ],
        ];
    }
}
