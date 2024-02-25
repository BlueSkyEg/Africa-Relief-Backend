<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Http\Requests\Stripe\PaymentMethod\SavePaymentMethodRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class SavePaymentMethodService extends BaseStripeService
{
    public function save(SavePaymentMethodRequest $request)
    {
        try {
            $paymentMethod = $this->stripe->paymentMethods->attach(
                $request->paymentMethodId,
                ['customer' => $this->stripeCustomerId ?? $request->customerId]
            );

            return response()->api(true, 'payment method saved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
