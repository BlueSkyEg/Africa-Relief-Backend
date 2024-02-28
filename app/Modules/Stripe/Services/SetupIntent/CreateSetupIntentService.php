<?php

namespace App\Modules\Stripe\Services\SetupIntent;

use App\Modules\Stripe\Services\BaseStripeService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\SetupIntent;

class CreateSetupIntentService extends BaseStripeService
{
    public function create(): JsonResponse
    {
        try {
            $intent = $this->stripe->setupIntents->create([
//                'customer' => $this->user->stripe_id,
                'payment_method_types' => ['card'],
            ]);

            return response()->api(true, 'intent created successfully', ['clientSecret' => $intent->client_secret]);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
