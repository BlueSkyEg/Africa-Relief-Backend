<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'subscription_id' => $this->subscription_id,
            'donation_form' => new DonationFormResource($this->donationForm),
            'payment_method' => new PaymentMethodResource($this->paymentMethod),
            'stripe_transaction_id' => $this->stripe_transaction_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'billing_comment' => $this->billing_comment,
            'completed_date' => $this->completed_date,
            'status' => $this->status,
            'anonymous_donation' => (bool)$this->anonymous_donation
        ];
    }
}
