<?php

namespace App\Modules\Firebase\DeviceToken\Services;

use App\Modules\Firebase\DeviceToken\FirebaseDeviceTokenRepository;
use App\Modules\Firebase\DeviceToken\Requests\StoreFirebaseDeviceTokenRequest;
use Illuminate\Http\JsonResponse;

class StoreFirebaseDeviceTokenService
{
    public function __construct(private readonly FirebaseDeviceTokenRepository $firebaseDeviceTokenRepository)
    {
    }

    public function storeDeviceToken(StoreFirebaseDeviceTokenRequest $request): JsonResponse
    {
        try {
            $this->firebaseDeviceTokenRepository->storeFirebaseDeviceToken([
                'user_id' => $request->user()?->id,
                'token' => $request->token
            ]);

            return response()->api(true, 'device token stored successfully');
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
