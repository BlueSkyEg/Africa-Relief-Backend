<?php

namespace App\Modules\Stripe\Services\PaymentMethod;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class RetrievePaymentMethodService extends BaseStripeService
{
    public function retrieve(string $paymentMethodId): JsonResponse
    {
        try {
            $paymentMethod = $this->stripe->customers->retrievePaymentMethod(
                $this->user->stripe_id,
                $paymentMethodId
            );

            return response()->api(true, 'payment method retrieved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
