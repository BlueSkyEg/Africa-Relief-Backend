<?php

namespace App\Services\Stripe\PaymentMethod;

use App\Http\Requests\Stripe\PaymentMethod\RetreivePaymentMethodRequest;
use App\Services\Stripe\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class RetrievePaymentMethodService extends BaseStripeService
{
    public function retrieve(RetreivePaymentMethodRequest $request)
    {
        try {
            $paymentMethod = $this->stripe->customers->retrievePaymentMethod(
                $this->stripeCustomerId ?? $request->customerId,
                $request->paymentMethodId
            );

            return response()->api(true, 'payment method retrieved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
