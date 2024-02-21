<?php

namespace App\Modules\Stripe\Services\PaymentMethod;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class UpdateDefaultPaymentMethodService extends BaseStripeService
{
    public function update(string $paymentMethodId): JsonResponse
    {
        try {
            $defaultPaymentMethod = $this->stripe->customers->update(
                $this->user->stripe_id,
                [
                    'invoice_settings' => ['default_payment_method' => $paymentMethodId]
                ]
            );

            return response()->api(true, 'default payment method updated successfully', $defaultPaymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
