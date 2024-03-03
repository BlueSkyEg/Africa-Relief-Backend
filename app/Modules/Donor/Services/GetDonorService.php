<?php

namespace App\Modules\Donor\Services;

use App\Modules\Donor\Repositories\DonorRepository;
use App\Modules\Stripe\Services\Customer\CreateCustomerService;
use App\Modules\User\Services\GetUserService;

class GetDonorService
{
    public function __construct(private DonorRepository $donorRepository, private GetUserService $getUserService, private CreateCustomerService $createCustomerService)
    {
    }

    public function getOrCreateDonor(string $name, string $email)
    {
        $donor = $this->donorRepository->getDonorByEmail($email);
        if ($donor) {
            return $donor;
        }

        $user = $this->getUserService->getAuthUser();
        $stripeCustomerId = $this->createCustomerService->create($name, $email)->id;
        return $this->donorRepository->createDonor($email, $stripeCustomerId, $user);
    }
}
