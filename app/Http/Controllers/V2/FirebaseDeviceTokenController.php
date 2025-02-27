<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Modules\Firebase\DeviceToken\Requests\StoreFirebaseDeviceTokenRequest;
use App\Modules\Firebase\DeviceToken\Services\StoreFirebaseDeviceTokenService;
use Illuminate\Http\JsonResponse;

class FirebaseDeviceTokenController extends Controller
{
    public function __construct(private readonly StoreFirebaseDeviceTokenService $storeDeviceTokenService)
    {
    }

    public function storeFirebaseDeviceToken(StoreFirebaseDeviceTokenRequest $request): JsonResponse
    {
        return $this->storeDeviceTokenService->storeDeviceToken($request);
    }
}
