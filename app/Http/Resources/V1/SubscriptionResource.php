<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'stripe_subscription_id' => $this->stripe_subscription_id,
            'donation_form' => new DonationFormResource($this->donationForm),
            'period' => $this->period,
            'initial_amount' => $this->initial_amount,
            'recurring_amount' => $this->recurring_amount,
            'completed_date' => $this->completed_date,
            'expiration_date' => $this->expiration_date,
            'status' => $this->status
        ];
    }
}
