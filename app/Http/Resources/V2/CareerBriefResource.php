<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerBriefResource extends JsonResource
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
        ];
    }
}
