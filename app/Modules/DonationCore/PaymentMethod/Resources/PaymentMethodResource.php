<?php

namespace App\Modules\DonationCore\PaymentMethod\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'exp_month' => $this->exp_month,
            'exp_year' => $this->exp_year,
            'last4' => $this->last4,
            'brand' => $this->brand,
            'wallet' => $this->wallet,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'street_address' => $this->street_address,
            'postal_code' => $this->postal_code,
            'default' => (bool)$this->default
        ];
    }
}
