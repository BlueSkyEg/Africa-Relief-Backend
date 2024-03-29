<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class DeletePaymentMethodService extends BaseStripeService
{
    public function delete(string $paymentMethodId): JsonResponse
    {
        try {
            $this->stripe->paymentMethods->detach($paymentMethodId);

            return response()->api(true, 'payment method deleted successfully');
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
