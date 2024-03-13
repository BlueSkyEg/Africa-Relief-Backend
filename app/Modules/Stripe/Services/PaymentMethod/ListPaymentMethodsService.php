<?php

namespace App\Modules\Stripe\Services\PaymentMethod;

use App\Modules\Stripe\Services\BaseStripeService;
use App\Modules\User\Services\GetUserService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class ListPaymentMethodsService extends BaseStripeService
{
    public function __construct(StripeClient $stripe, private GetUserService $getUserService)
    {
        parent::__construct($stripe);
    }

    public function list(): JsonResponse
    {
        try {
            $user = $this->getUserService->getAuthUser();
            $paymentMethods = $this->stripe->customers->allPaymentMethods(
                $user->donor->stripe_customer_id
            );

            return response()->api(true, 'payment methods retrieved successfully', $paymentMethods->data);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
