<?php

namespace App\Modules\User\Services;

use App\Modules\Authentication\Requests\RegisterRequest;
use App\Modules\User\Repositories\UserRepository;

class CreateUserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function createUser(RegisterRequest $request)
    {
        return $this->userRepository->createUser($request);
    }
}
