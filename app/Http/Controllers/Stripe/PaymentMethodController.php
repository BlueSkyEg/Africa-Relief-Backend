<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\PaymentMethod\SavePaymentMethodRequest;
use App\Http\Requests\Stripe\PaymentMethod\UpdateDefaultPaymentMethodRequest;
use App\Http\Requests\Stripe\PaymentMethod\UpdatePaymentMethodRequest;
use App\Services\Stripe\PaymentMethod\DeletePaymentMethodService;
use App\Services\Stripe\PaymentMethod\ListPaymentMethodsService;
use App\Services\Stripe\PaymentMethod\RetrievePaymentMethodService;
use App\Services\Stripe\PaymentMethod\SavePaymentMethodService;
use App\Services\Stripe\PaymentMethod\UpdateDefaultPaymentMethodService;
use App\Services\Stripe\PaymentMethod\UpdatePaymentMethodService;
use App\Services\Stripe\SetupIntent\CreateSetupIntentService;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    public function setupPaymentMethodIntent(CreateSetupIntentService $createSetupIntentService): JsonResponse
    {
        return $createSetupIntentService->create();
    }

    public function savePaymentMethod(SavePaymentMethodService $savePaymentMethodService, SavePaymentMethodRequest $request): JsonResponse
    {
        return $savePaymentMethodService->save($request->paymentMethodId);
    }

    public function updatePaymentMethod(UpdatePaymentMethodService $updatePaymentMethodService, UpdatePaymentMethodRequest $request): JsonResponse
    {
        return $updatePaymentMethodService->update($request);
    }

    public function retrieveAllPaymentMethods(ListPaymentMethodsService $listPaymentMethodsService): JsonResponse
    {
        return $listPaymentMethodsService->list();
    }

    public function retrievePaymentMethod(RetrievePaymentMethodService $retrievePaymentMethodService, string $paymentMethodId): JsonResponse
    {
        return $retrievePaymentMethodService->retrieve($paymentMethodId);
    }

    public function updateDefaultPaymentMethod(UpdateDefaultPaymentMethodService $defaultPaymentMethodService, UpdateDefaultPaymentMethodRequest $request): JsonResponse
    {
        return $defaultPaymentMethodService->update($request->paymentMethodId);
    }

    public function deletePaymentMethod(DeletePaymentMethodService $deletePaymentMethodService, string $paymentMethodId): JsonResponse
    {
        return $deletePaymentMethodService->delete($paymentMethodId);
    }
}
