<?php

namespace App\Modules\Donor\Services;

use App\Modules\Donor\Repositories\DonorRepository;
use App\Modules\Stripe\Services\StripeCustomerService;
use App\Modules\Stripe\Services\StripePaymentMethodService;
use App\Modules\User\Services\GetUserService;

class GetDonorService
{
    public function __construct(
        private readonly DonorRepository            $donorRepository,
        private readonly GetUserService             $getUserService,
        private readonly StripeCustomerService      $stripeCustomerService,
        private readonly StripePaymentMethodService $stripePaymentMethodService
    )
    {
    }

    public function getDonorByEmail(string $email)
    {
        return $this->donorRepository->getDonorByEmail($email);
    }

    public function getDonorByStripeCustomerId(string $stripeCustomerId)
    {
        return $this->donorRepository->getDonorByStripeCustomerId($stripeCustomerId);
    }

    public function getOrCreateDonor(string $name, string $email, string $paymentMethodId, bool $savePaymentMethod)
    {
        $user = $this->getUserService->getAuthUser();
        $donor = $user?->donor;

        // check if user has existing donor
        if ($donor) {
            if ($savePaymentMethod) {
                $this->stripePaymentMethodService->attachPaymentMethod($paymentMethodId, $donor->stripe_customer_id);
            }
            return $donor;
        }

        // check if donor exists but not attached to user
        $donor = $this->getDonorByEmail($user->email ?? $email);
        if ($donor) {
            if ($savePaymentMethod) {
                $this->stripePaymentMethodService->attachPaymentMethod($paymentMethodId, $donor->stripe_customer_id);
            }
            if ($user) {
                $donor->update(['user_id' => $user->id]);
            }
            return $donor;
        }

        // create new donor
        $stripeCustomerId = $this->stripeCustomerService->createCustomer($name, $email)->id;
        if ($savePaymentMethod) {
            $this->stripePaymentMethodService->attachPaymentMethod($paymentMethodId, $stripeCustomerId);
        }
        return $this->donorRepository->createDonor($email, $stripeCustomerId, $user);
    }
}
