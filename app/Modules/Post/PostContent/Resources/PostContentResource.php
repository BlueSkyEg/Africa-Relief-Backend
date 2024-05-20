<?php

namespace App\Modules\Post\PostContent\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'heading' => $this->heading,
            'description' => $this->description
        ];
    }
}
