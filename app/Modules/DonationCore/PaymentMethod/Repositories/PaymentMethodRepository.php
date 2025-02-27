<?php

namespace App\Modules\DonationCore\PaymentMethod\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository
{
    /**
     * @param array $attributes
     * @return PaymentMethod
     */
    public function updateOrCreate(array $attributes): PaymentMethod
    {
        return PaymentMethod::updateOrCreate(...$attributes);
    }
}
