<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "username" => $this->username,
            "phone" => $this->phone,
            "address" => $this->address,
            "img" => $this->img ? asset('storage/users/images/'.$this->img) : null,
            "active" => $this->active,
            //'total_donation_spent' => (string)$this->donations()->sum('amount'),
            //'donation_count' => $this->donations()->count(),
            'total_donation_spent' => (string)$this->donations()->where('status', 'succeeded')->sum('amount'),
            'donation_count' => $this->donations()->where('status', 'succeeded')->count(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
