<?php

namespace App\Modules\Firebase\DeviceToken;

use App\Models\FirebaseDeviceToken;

class FirebaseDeviceTokenRepository
{
    public function getDevicesTokens(): array
    {
        return FirebaseDeviceToken::pluck('token')->toArray();
    }

    public function storeFirebaseDeviceToken(array $attributes): FirebaseDeviceToken
    {
        return FirebaseDeviceToken::create($attributes);
    }
}
