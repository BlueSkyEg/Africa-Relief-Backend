<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\Donor\StoreDonorService;
use App\Services\Donation\StoreDonationService;
use App\Services\Subscription\StoreSubscribtionService;

class StorePaymentService
{
    public function __construct(
        public StoreDonorService $storeDonorService,
        public StoreDonationService $storeDonationService,
        public StoreSubscribtionService $storeSubscriptionService
    ) {
    }
    /**
     * Process and store payment information into the database.
     *
     * @param array $paymentIntentData Payment data received from Stripe
     * @param array|null $subscriptionData Subscription data received from Stripe (optional)
     * @return void
    */

    public function processStorePaymentIntoDB(object $paymentIntentData, $subscriptionData = null)
    {
        // If the user is authenticated, retrieve their donor record
        $user = JWTAuth::user();
        if ($user) {
            $donor = $user->donor;
        } else {
            // If guest authenticated, create or update the donor record based on Stripe customer data
            $donor = $this->storeDonorService->createOrUpdateDonorRecord($paymentIntentData);
        }

        // Create a donation record
        $donation = $this->storeDonationService->createDonationRecord($donor, $paymentIntentData);

        // Create Subscription record if exist
        if ($subscriptionData) {
            $this->storeSubscriptionService->createSubscriptionRecord($donor, $donation, $subscriptionData);
        }
    }
}
