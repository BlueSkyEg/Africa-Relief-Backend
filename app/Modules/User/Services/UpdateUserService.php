<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Repositories\UserRepository;

class UpdateUserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function updateUserPassword(User $user, string $newPassword)
    {
        return $this->userRepository->updateUserPassword($user, $newPassword);
    }
}
