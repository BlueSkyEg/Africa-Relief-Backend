<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Admin;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    /**
     * @param $adminId
     * @return Admin|null
     */
    public function find($adminId): ?Admin
    {
        return Admin::find($adminId);
    }

    /**
     * @param string $emailOrUsername
     * @return Admin|null
     */
    public function findByEmailOrUsername(string $emailOrUsername): ?Admin
    {
        return Admin::where('email', $emailOrUsername)->orWhere('username', $emailOrUsername)->first();
    }


    /**
     * @param array $credentials
     * @return Admin
     */
    public function create(array $credentials): Admin
    {
        return Admin::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);
    }

    /**
     * @param $admin
     * @param array $info
     * @return Admin
     */
    public function updateInfo($admin, array $info): Admin
    {
        $admin->fill($info);
        $admin->save();

        return $admin;
    }
}
