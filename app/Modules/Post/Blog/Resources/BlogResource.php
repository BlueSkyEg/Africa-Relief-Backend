<?php

namespace App\Modules\Post\Blog\Resources;

use App\Modules\DonationForm\Resources\DonationFormResource;
use App\Modules\Post\PostCategory\Resources\PostCategoryResource;
use App\Modules\Post\PostContent\Resources\PostContentResource;
use App\Modules\Image\Resources\ImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'location' => $this->location,
            'implementation_date' => $this->implementation_date,
            'categories' => PostCategoryResource::collection($this->post->categories),
            'contents' => PostContentResource::collection($this->post->contents),
            'featured_image' => new ImageResource($this->featuredImage),
            'gallery' => ImageResource::collection($this->post->images),
            'donation_form' => new DonationFormResource($this->donationForm),
            'meta_title' => $this->post->meta_title,
            'meta_keywords' => $this->post->meta_keywords,
            'meta_description' => $this->post->meta_description,
            'meta_robots' => $this->post->meta_robots,
            'meta_og_title' => $this->post->meta_og_title,
            'meta_og_type' => $this->post->meta_og_type,
            'created_at' => $this->post->created_at,
            'updated_at' => $this->post->updated_at
        ];
    }
}