<?php

namespace App\Modules\DonationCore\Donor\Services;

use App\Modules\DonationCore\Donor\Donor;
use App\Modules\DonationCore\Donor\Repositories\DonorRepository;
use App\Modules\DonationCore\Stripe\Exceptions\StripeApiException;
use App\Modules\DonationCore\Stripe\Services\StripeCustomerService;
use App\Modules\User\Services\UserService;

class DonorService
{
    public function __construct(
        private readonly UserService           $getUserService,
        private readonly DonorRepository       $donorRepository,
        private readonly StripeCustomerService $stripeCustomerService,
    )
    {
    }


    /**
     * @param string $email
     * @return Donor|null
     */
    public function getDonorByEmail(string $email): ?Donor
    {
        return $this->donorRepository->getByEmail($email);
    }


    /**
     * @param string $stripeCustomerId
     * @return Donor|null
     */
    public function getDonorByStripeCustomerId(string $stripeCustomerId): ?Donor
    {
        return $this->donorRepository->getByStripeCustomerId($stripeCustomerId);
    }


    /**
     * @param array $attributes
     * @return Donor|null
     */
    public function createDonor(array $attributes): ?Donor
    {
        return $this->donorRepository->create($attributes);
    }


    /**
     * @param array $attributes
     * @param null $user
     * @return Donor|null
     * @throws StripeApiException
     */
    public function getOrCreateDonor(array $attributes, $user = null): ?Donor
    {
        $user = $user ?? $this->getUserService->getAuthUser();
        $donor = $user?->donor;

        // check if user has existing donor
        if ($donor) return $donor;

        // check if donor exists but not attached to user
        $donor = $this->getDonorByEmail($user?->email ?? $attributes['email']);
        if ($donor) {
            if ($user) {
                $donor->user_id = $user->id;
                $donor->save();
            }
            return $donor;
        }

        // Create Stripe Customer and retrieve the customer id to create new donor
        $stripeCustomerId = $this->stripeCustomerService->createCustomer($attributes['name'], $attributes['email'])->id;
        return $this->createDonor([
            'user_id' => $user?->id,
            'email' => $attributes['email'],
            'stripe_customer_id' => $stripeCustomerId
        ]);
    }
}
