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
}
