<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Http\Requests\User\UpdateUserInfoRequest;

class UpdateUserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function updateUserPassword(User $user, string $newPassword)
    {
        return $this->userRepository->updateUserPassword($user, $newPassword);
    }

    public function updateUserInfo(UpdateUserInfoRequest $request) {
        $user = $request->user();

        if($request->filled('name') && $user->name != $request->name) {
            $user->name = $request->name;
        }

        if($request->filled('email') && $user->email != $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        if($request->filled('phone') && $user->phone != $request->phone) {
            $user->phone = $request->phone;
        }

        if($request->filled('address') && $user->address != $request->address) {
            $user->address = $request->address;
        }

        if($request->filled('username') && $user->username != $request->username) {
            $user->username = $request->username;
        }

        return $this->userRepository->updateUserInfo($user);
    }
}
