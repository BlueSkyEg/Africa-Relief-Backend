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
                'payment_method_types' => ['card'],
            ]);

            return response()->api(true, 'intent created successfully', $intent);
        } catch (ApiErrorException $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
