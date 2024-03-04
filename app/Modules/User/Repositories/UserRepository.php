<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use MikeMcLin\WpPassword\Facades\WpPassword;

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

    public function updateUserPassword(User $user, string $newPassword)
    {
        $user->password = WpPassword::make($newPassword);
        return $user->save();
    }
}
