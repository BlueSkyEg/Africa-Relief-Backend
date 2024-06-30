<?php

namespace App\Modules\User\Services;

use App\Exceptions\ApiResponseException;
use App\Models\User;
use App\Modules\Authentication\Services\EmailVerificationService;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateUserService
{
    public function __construct(
        private readonly UserRepository           $userRepository,
        private readonly GetUserService           $getUserService,
        private readonly EmailVerificationService $emailVerificationService
    )
    {
    }

    public function updateUserInfo(array $info): User
    {
        $user = isset($info['id'])
            ? $this->getUserService->getUserById($info['id'])
            : $this->getUserService->getAuthUser();

        if (isset($info['email']) && $user->email !== $info['email']) {
            $info = array_merge($info, ['email_verified_at' => null]);
            $this->emailVerificationService->resendEmailVerificationNotification();
        }

        try {
            $updatedUser = $this->userRepository->updateInfo($user, $info);

            if (!$updatedUser) {
                throw new ApiResponseException('Failed to update user info.');
            }

            return $updatedUser;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while updating the user information: ' . $e->getMessage());
        }
    }

    public function updateUserImage(UploadedFile $image): User
    {
        $user = $this->getUserService->getAuthUser();

        try {
            $imagePath = $image->store('users/images');

            // Check if the user already has an image before attempting to delete
            if ($user->img) {
                Storage::delete('users/images/' . $user->img);
            }

            $imageName = Str::afterLast($imagePath, '/');
            $updatedUser = $this->userRepository->updateImage($user, $imageName);

            if (!$updatedUser) {
                throw new ApiResponseException('Failed to update user image.');
            }

            return $updatedUser;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while updating the user image: ' . $e->getMessage());
        }
    }

    public function updateUserPassword(User $user, string $newPassword): User
    {
        try {
            $updatedUser = $this->userRepository->updatePassword($user, $newPassword);

            if (!$updatedUser) {
                throw new ApiResponseException('Failed to update user password.');
            }

            return $updatedUser;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while updating the user password: ' . $e->getMessage());
        }
    }

    public function deactivateUser($userId = null): User
    {
        $user = $userId
            ? $this->getUserService->getUserById($userId)
            : $this->getUserService->getAuthUser();

        try {
            $deactivatedUser = $this->userRepository->deactivate($user);

            if (!$deactivatedUser) {
                throw new ApiResponseException('Failed to deactivate the user.');
            }

            return $deactivatedUser;
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while deactivating the user: ' . $e->getMessage());
        }
    }
}
