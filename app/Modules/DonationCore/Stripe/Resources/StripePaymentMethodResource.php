<?php

namespace App\Modules\DonationCore\Stripe\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StripePaymentMethodResource extends JsonResource
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
            "billing_details" => [
                "address" => [
                    "city" => $this->billing_details->address->city,
                    "country" => $this->billing_details->address->country,
                    "line1" => $this->billing_details->address->line1,
                    "line2" => $this->billing_details->address->line2,
                    "postal_code" => $this->billing_details->address->postal_code,
                    "state" => $this->billing_details->address->state
                ],
                "email" => $this->billing_details->email,
                "name" => $this->billing_details->name,
                "phone" => $this->billing_details->phone
            ],
        ];
    }
}
