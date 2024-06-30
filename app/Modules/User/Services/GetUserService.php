<?php

namespace App\Modules\User\Services;

use App\Exceptions\ApiResponseException;
use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class GetUserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getAuthUser(): Authenticatable
    {
        try {
            $user = auth()->user();

            if (!$user) {
                throw new ApiResponseException('Failed to get the authed user.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while getting the user: ' . $e->getMessage());
        }
    }

    public function getUserById($id): User
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                throw new ApiResponseException('Failed to get the user.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while getting the user: ' . $e->getMessage());
        }
    }

    public function getUserByEmailOrUsername(string $emailOrUsername): User
    {
        try {
            $user = $this->userRepository->findByEmailOrUsername($emailOrUsername);

            if (!$user) {
                throw new ApiResponseException('Failed to get the authed user.');
            }

            return $user;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while getting the user: ' . $e->getMessage());
        }
    }
}
