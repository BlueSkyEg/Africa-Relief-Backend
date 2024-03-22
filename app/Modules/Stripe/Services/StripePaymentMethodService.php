<?php

namespace App\Modules\Stripe\Services;

use App\Modules\User\Services\GetUserService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripePaymentMethodService extends BaseStripeService
{
    public function __construct(StripeClient $stripe, private readonly GetUserService $getUserService)
    {
        parent::__construct($stripe);
    }

    /*
     * Delete Payment Method
     * This method detach the payment method
     * from stripe customer, It won't be retrieved
     * again when get stripe customer's payment methods
     * and can't be used for future payment
     */
    public function deletePaymentMethod(string $paymentMethodId): JsonResponse
    {
        try {
            $this->stripe->paymentMethods->detach($paymentMethodId);

            return response()->api(true, 'payment method deleted successfully');
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    /*
     * List Payment Methods
     * This method get all the payment methods
     * that attached to stripe customer
     */
    public function listPaymentMethods(): JsonResponse
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

    /*
     * Get Payment Method
     * This method get specific payment method
     * that attached to stripe customer
     */
    public function getPaymentMethod(string $paymentMethodId): JsonResponse
    {
        try {
            $user = $this->getUserService->getAuthUser();
            $paymentMethod = $this->stripe->customers->retrievePaymentMethod(
                $user->donor->stripe_customer_id,
                $paymentMethodId
            );

            return response()->api(true, 'payment method retrieved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }

    /*
     * Attach Payment Method
     * This method attach a payment method
     * to stripe customer to use it in future payment
     */
    public function attachPaymentMethod(string $paymentMethodId, string $customerId): JsonResponse
    {
        try {
            $paymentMethod = $this->stripe->paymentMethods->attach(
                $paymentMethodId,
                ['customer' => $customerId]
            );

            return response()->api(true, 'payment method saved successfully', $paymentMethod);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
