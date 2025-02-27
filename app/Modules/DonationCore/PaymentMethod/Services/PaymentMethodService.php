<?php

namespace App\Modules\DonationCore\PaymentMethod\Services;

use App\Models\PaymentMethod;
use App\Modules\DonationCore\PaymentMethod\Repositories\PaymentMethodRepository;

class PaymentMethodService
{
    public function __construct(private readonly PaymentMethodRepository $paymentMethodRepository)
    {
    }

    /**
     * @param array $attributes
     * @return PaymentMethod
     */
    public function updateOrCreatePaymentMethod(array $attributes): PaymentMethod
    {
        return $this->paymentMethodRepository->updateOrCreate($attributes);
    }
}
