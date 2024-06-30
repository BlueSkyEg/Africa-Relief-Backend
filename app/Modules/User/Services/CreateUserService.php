<?php

namespace App\Modules\User\Services;

use App\Exceptions\ApiResponseException;
use App\Models\User;
use App\Modules\User\Repositories\UserRepository;

class CreateUserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function createUser(array $credentials): User
    {
        try {
            $user = $this->userRepository->create($credentials);

            if (!$user) {
                throw new ApiResponseException('Failed to create new user.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while creating user: ' . $e->getMessage());
        }
    }
}
