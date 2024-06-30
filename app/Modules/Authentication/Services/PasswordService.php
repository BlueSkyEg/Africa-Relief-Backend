<?php

namespace App\Modules\Authentication\Services;

use App\Exceptions\ApiResponseException;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class PasswordService
{
    public function __construct(
        private readonly GetUserService    $getUserService,
        private readonly UpdateUserService $updateUserService
    )
    {
    }

    // Send password reset link request
    public function sendPasswordResetLink(array $email): void
    {
        try {
            $status = Password::sendResetLink($email);

            $this->throwPasswordException($status);
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while sending password reset link: ' . $e->getMessage());
        }
    }

    // Create new password
    public function createNewPassword(array $credentials): void
    {
        try {
            $status = Password::reset(
                $credentials,
                function ($user) use ($credentials) {
                    $user = $this->updateUserService->updateUserPassword($user, $credentials['password']);
                    $user->tokens()->delete();

                    event(new PasswordReset($user));
                }
            );

            $this->throwPasswordException($status);
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred during create new password: ' . $e->getMessage());
        }
    }

    // Change password with the current password
    public function changePassword(array $credentials): void
    {
        $user = $this->getUserService->getAuthUser();

        try {
            // Check request password with user password that saved in database
            // WpPassword can check password if it from WordPress or Laravel password
            if (!WpPassword::check($credentials['currentPassword'], $user->password)) {
                throw ValidationException::withMessages([
                    'currentPassword' => 'The current password is incorrect.'
                ]);
            }

            $user = $this->updateUserService->updateUserPassword($user, $credentials['password']);
            $user->tokens()->delete();
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while change the password: ' . $e->getMessage());
        }
    }

    private function throwPasswordException(string $status): void
    {
        if ($status !== Password::RESET_LINK_SENT) {
            if ($status === Password::RESET_THROTTLED) {
                $errorMsg = 'You have exceeded the trying limit. Please try again later.';
            } else {
                $errorMsg = 'An error occurred during the reset password process.';
            }

            throw new ApiResponseException($errorMsg);
        }
    }
}
