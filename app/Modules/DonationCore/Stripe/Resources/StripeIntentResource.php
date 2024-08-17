<?php

namespace App\Modules\DonationCore\Stripe\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StripeIntentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->object,
            'client_secret' => $this->client_secret,
            'status' => $this->status
        ];
    }
}
