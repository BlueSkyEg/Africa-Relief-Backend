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
        return User::where('email', $email)->first();
    }

    public function getUserByEmailOrUsername(string $emailOrUsername)
    {
        return User::where('email', $emailOrUsername)->orWhere('username', $emailOrUsername)->first();
    }

    public function updateUserPassword(User $user, string $newPassword)
    {
        $user->password = WpPassword::make($newPassword);
        return $user->save();
    }
}
