<?php

namespace App\Modules\Firebase\DeviceToken\Services;

use App\Modules\Firebase\DeviceToken\FirebaseDeviceTokenRepository;
use Illuminate\Http\JsonResponse;

class GetFirebaseDeviceTokenService
{
    public function __construct(private readonly FirebaseDeviceTokenRepository $firebaseDeviceTokenRepository)
    {
    }

    public function getFirebaseDevicesToken(): JsonResponse
    {
        try {
            $devicesTokens = $this->firebaseDeviceTokenRepository->getDevicesTokens();

            return response()->api(true, 'devices tokens retrieved successfully', $devicesTokens);
        } catch (\Exception $e) {
            return response()->api(false, $e->getMessage());
        }
    }
}
