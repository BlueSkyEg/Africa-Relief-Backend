<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'donor_id' => $this->donor_id,
            'donation_id' => $this->donation_id,
            'stripe_subscription_id' => $this->stripe_subscription_id,
            'period' => $this->period,
            'amount' => $this->amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
