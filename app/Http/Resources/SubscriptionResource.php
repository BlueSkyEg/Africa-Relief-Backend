<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'period' => $this->period,
            'frequency' => $this->frequency,
            'amount' => $this->amount,
            'fee_amount' => $this->fee_amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
