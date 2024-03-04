<?php

namespace App\Modules\User\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAuthUser()
    {
        return auth('sanctum')->user();
    }

    public function getUserByEmail(string $email)
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Exception $e) {
            return response()->api(false, 'user not found', $e->getMessage());
        }
    }
}
