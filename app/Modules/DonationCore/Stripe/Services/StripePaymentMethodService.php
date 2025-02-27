<?php

namespace App\Modules\DonationCore\Stripe\Services;

use App\Models\Donor;
use App\Modules\DonationCore\Stripe\Exceptions\StripeApiException;
use Stripe\Exception\ApiErrorException;

class StripePaymentMethodService extends BaseStripeService
{

    /**
     * Attach Payment Method
     * This method attach a payment method
     * to stripe customer to use it in future payment
     *
     * @param string $stripePaymentMethodId
     * @param Donor $donor
     * @return \Stripe\PaymentMethod
     * @throws StripeApiException
     */
    public function attachPaymentMethod(string $stripePaymentMethodId, Donor $donor): \Stripe\PaymentMethod
    {
        try {
            return $this->stripe->paymentMethods->attach(
                $stripePaymentMethodId,
                ['customer' => $donor->stripe_customer_id]
            );

        } catch (ApiErrorException $e) {
            throw new StripeApiException("An error occurred while attaching the payment method to the donor.");
        }
    }
}
