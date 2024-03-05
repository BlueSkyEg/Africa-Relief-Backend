<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserRepository;

class GetUserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getAuthUser()
    {
        return $this->userRepository->getAuthUser();
    }

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function getUserByEmailOrUsername(string $emailOrUsername)
    {
        return $this->userRepository->getUserByEmailOrUsername($emailOrUsername);
    }
}
