<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            // 'subscription_id' => $this->subscription_id,
            'project_title' => $this->project_title,
            'amount' => $this->amount,
            'stripe_transaction_id' => $this->stripe_transaction_id,
            'currency' => $this->currency,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'address1' => $this->address1,
            'address2' => $this->address2,
        ];
    }
}
