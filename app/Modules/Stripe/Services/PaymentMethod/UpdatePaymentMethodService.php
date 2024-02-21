<?php

namespace App\Modules\Stripe\Services\PaymentMethod;

use App\Http\Requests\Stripe\PaymentMethod\UpdatePaymentMethodRequest;
use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class UpdatePaymentMethodService extends BaseStripeService
{
    public function update(UpdatePaymentMethodRequest $request): JsonResponse
    {
        try {
            $paymentMethod = $this->stripe->paymentMethods->update(
                $request->paymentMethodId,
                [
                    'billing_details' => [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address
                    ]
                ]
            );

            return response()->api(true, 'payment method updated successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
