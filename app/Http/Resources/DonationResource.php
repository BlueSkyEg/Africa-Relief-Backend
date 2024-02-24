<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'donor_id' => $this->donor_id,
            'subscription_id' => $this->subscription_id,
            'project_title' => $this->project_title,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'ip_address' => $this->ip_address,
            'payment_mode' => $this->payment_mode,
            'payment_gateway' => $this->payment_gateway,
            'payment_transaction_id' => $this->payment_transaction_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'address1' => $this->address1,
            'address2' => $this->address2,
        ];
    }
}
