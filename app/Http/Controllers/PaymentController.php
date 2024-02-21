<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\DonationRequest;
use App\Http\Requests\Payment\SubscriptionRequest;
use App\Services\DonationService;
use App\Services\SubscriptionService;

class PaymentController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
        protected SubscriptionService $subscriptionService
    )
    {
    }

    public function storeDonation(DonationRequest $request)
    {
        $response = $this->donationService->processDonation($request->validated());

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json(['message' => $response['message'], 'donation' => $response['donation']], 200);
    }

    public function storeSubscription(SubscriptionRequest $request)
    {
        $response = $this->subscriptionService->processSubscription($request->validated());

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json(['message' => $response['message'], 'subscription' => $response['subscription']], 200);
    }
}
