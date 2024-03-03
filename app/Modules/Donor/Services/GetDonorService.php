<?php

namespace App\Modules\Donor\Services;

use App\Modules\Donor\Repositories\DonorRepository;
use App\Modules\Stripe\Services\Customer\CreateCustomerService;
use App\Modules\Stripe\Services\PaymentMethod\SavePaymentMethodService;
use App\Modules\User\Services\GetUserService;

class GetDonorService
{
    public function __construct(
        private DonorRepository $donorRepository,
        private GetUserService $getUserService,
        private CreateCustomerService $createCustomerService,
        private SavePaymentMethodService $savePaymentMethodService
    )
    {
    }

    public function getOrCreateDonor(string $name, string $email, string $paymentMethodId, bool $savePaymentMethod)
    {
        $donor = $this->donorRepository->getDonorByEmail($email);
        if ($donor) {
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
