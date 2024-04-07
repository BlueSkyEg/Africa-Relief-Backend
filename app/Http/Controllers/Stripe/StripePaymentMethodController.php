<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Modules\Stripe\Requests\PaymentMethod\attachPaymentMethodRequest;
use App\Modules\Stripe\Services\StripePaymentMethodService;
use Illuminate\Http\JsonResponse;

class StripePaymentMethodController extends Controller
{
    public function __construct(private readonly StripePaymentMethodService $stripePaymentMethodService)
    {
    }

    /*
     * Attach Payment Method
     * This method attach a payment method
     * to stripe customer to use it in future payment
     */
    public function attachPaymentMethod(attachPaymentMethodRequest $request): JsonResponse
    {
        return $this->stripePaymentMethodService->attachPaymentMethod($request->paymentMethodId, $request->stripeCustomerId);
    }

    /*
     * List Payment Methods
     * This method get all the payment methods
     * that attached to stripe customer
     */
    public function listPaymentMethods(): JsonResponse
    {
        return $this->stripePaymentMethodService->listPaymentMethods();
    }

    /*
     * Get Payment Method
     * This method get specific payment method
     * that attached to stripe customer
     */
    public function getPaymentMethod(string $paymentMethodId): JsonResponse
    {
        return $this->stripePaymentMethodService->getPaymentMethod($paymentMethodId);
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
        return $this->stripePaymentMethodService->deletePaymentMethod($paymentMethodId);
    }
}
