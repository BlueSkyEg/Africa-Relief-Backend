<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function find($userId): ?User
    {
        $user = User::find($userId);

        return $user ?: null;
    }

    public function findByEmailOrUsername(string $emailOrUsername): ?User
    {
        $user = User::where('email', $emailOrUsername)->orWhere('username', $emailOrUsername)->first();

        return $user ?: null;
    }

    public function updatePassword(User $user, string $newPassword): ?User
    {
        $user->password = Hash::make($newPassword);

        return $user->save() ? $user : null;
    }

    public function create(array $credentials): ?User
    {
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        return $user ?: null;
    }

    public function updateInfo($user, array $info): ?User
    {
        $user->fill($info);

        return $user->save() ? $user : null;
    }

    public function updateImage($user, string $imageName): ?User
    {
        $user->img = $imageName;

        return $user->save() ? $user : null;
    }

    public function deactivate($user): ?User
    {
        $user->active = '0';

        return $user->save() ? $user : null;
    }
}
