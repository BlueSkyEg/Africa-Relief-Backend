<?php

namespace App\Modules\Stripe\Services\PaymentMethod;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class ListPaymentMethodsService extends BaseStripeService
{
    public function list(): JsonResponse
    {
        try {
            $paymentMethods = $this->stripe->customers->allPaymentMethods(
                $this->user->stripe_id
            );

            return response()->api(true, 'payment methods retrieved successfully', $paymentMethods->data);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
