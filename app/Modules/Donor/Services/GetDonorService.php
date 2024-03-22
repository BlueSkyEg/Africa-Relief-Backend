<?php

namespace App\Modules\Donor\Services;

use App\Modules\Donor\Repositories\DonorRepository;
use App\Modules\Stripe\Services\Customer\CreateCustomerService;
use App\Modules\Stripe\Services\PaymentMethod\SavePaymentMethodService;
use App\Modules\User\Services\GetUserService;

class GetDonorService
{
    public function __construct(
        private readonly DonorRepository $donorRepository,
        private readonly GetUserService $getUserService,
        private readonly CreateCustomerService $createCustomerService,
        private readonly SavePaymentMethodService $savePaymentMethodService
    )
    {
    }

    public function getOrCreateDonor(string $name, string $email, string $paymentMethodId, bool $savePaymentMethod)
    {
        $donor = $this->donorRepository->getDonorByEmail($email);
        if ($donor) {
            if ($savePaymentMethod) {
                $this->savePaymentMethodService->SavePaymentMethod($paymentMethodId, $donor->stripe_customer_id);
            }
            return $donor;
        }

        $user = $this->getUserService->getAuthUser();
        $stripeCustomerId = $this->createCustomerService->create($name, $email)->id;
        if ($savePaymentMethod) {
            $this->savePaymentMethodService->SavePaymentMethod($paymentMethodId, $stripeCustomerId);
        }
        return $this->donorRepository->createDonor($email, $stripeCustomerId, $user);
    }
}
