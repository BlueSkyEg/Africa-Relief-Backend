<?php

namespace App\Modules\Firebase\DeviceToken\Repositories;

use App\Modules\Firebase\DeviceToken\FirebaseDeviceToken;

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
