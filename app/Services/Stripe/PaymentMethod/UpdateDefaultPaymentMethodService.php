<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Http\Requests\Stripe\PaymentMethod\UpdateDefaultPaymentMethodRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class UpdateDefaultPaymentMethodService extends BaseStripeService
{
    public function update(UpdateDefaultPaymentMethodRequest $request): JsonResponse
    {
        try {
            $defaultPaymentMethod = $this->stripe->customers->update(
                $this->stripeCustomerId ?? $request->customerId,
                [
                    'invoice_settings' => ['default_payment_method' => $request->paymentMethodId]
                ]
            );

            return response()->api(true, 'default payment method updated successfully', $defaultPaymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
