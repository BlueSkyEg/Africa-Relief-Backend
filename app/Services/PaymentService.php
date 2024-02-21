<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;

class PaymentService
{
    public function processPayment(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric',
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()->first()];
        }

        // Store donation
        $donation = Donation::create([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            // Add other donation fields
        ]);

        // Check if there's a subscription
        if (isset($data['subscription_id'])) {
            $subscription = Subscription::find($data['subscription_id']);
            if ($subscription) {
                // Update subscription status or perform other actions
            } else {
                // Handle subscription not found
            }
        }

        return ['message' => 'Payment stored successfully', 'donation' => $donation];
    }
}
