<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Http\Requests\Stripe\PaymentMethod\ListPaymentMethodsRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class ListPaymentMethodsService extends BaseStripeService
{
    public function list(ListPaymentMethodsRequest $request): JsonResponse
    {
        try {
            $paymentMethods = $this->stripe->customers->allPaymentMethods(
                $this->stripeCustomerId ?? $request->customerId
            );

            return response()->api(true, 'payment methods retrieved successfully', $paymentMethods->data);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
