<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param $userId
     * @return User|null
     */
    public function find($userId): ?User
    {
        return User::find($userId);
    }

    /**
     * @param string $emailOrUsername
     * @return User|null
     */
    public function findByEmailOrUsername(string $emailOrUsername): ?User
    {
        return User::where('email', $emailOrUsername)->orWhere('username', $emailOrUsername)->first();
    }


    /**
     * @param array $credentials
     * @return User
     */
    public function create(array $credentials): User
    {
        return User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);
    }

    /**
     * @param $user
     * @param array $info
     * @return User
     */
    public function updateInfo($user, array $info): User
    {
        $user->fill($info);
        $user->save();

        return $user;
    }
}
