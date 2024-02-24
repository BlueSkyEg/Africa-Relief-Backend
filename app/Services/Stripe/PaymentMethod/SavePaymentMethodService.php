<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class SavePaymentMethodService extends BaseStripeService
{
    public function save(string $paymentMethodId): JsonResponse
    {
        try {
            $paymentMethod = $this->stripe->paymentMethods->attach(
                $paymentMethodId,
                ['customer' => $this->user->stripe_id]
            );

            return response()->api(true, 'payment method saved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
