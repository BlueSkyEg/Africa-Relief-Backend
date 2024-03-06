<?php

namespace App\Modules\User\Repositories;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    // Get authed user
    public function getAuthUser()
    {
        return auth('sanctum')->user();
    }

    // Get user by email
    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    // Get user by email or username
    public function getUserByEmailOrUsername(string $emailOrUsername)
    {
        return User::where('email', $emailOrUsername)->orWhere('username', $emailOrUsername)->first();
    }

    // Update user password
    public function updateUserPassword(User $user, string $newPassword)
    {
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }

    // Create new user
    public function createUser(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }
}
