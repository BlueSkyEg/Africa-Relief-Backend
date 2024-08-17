<?php

namespace App\Modules\Authentication\Services;

use App\Exceptions\ApiException;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Services\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class PasswordService
{
    public function __construct(private readonly UserService $userService)
    {
    }


    /**
     * Send password reset link request
     *
     * @param array $email
     * @return void
     * @throws ApiException
     */
    public function sendPasswordResetLink(array $email): void
    {
        $status = Password::sendResetLink($email);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new ApiException('Invalid user.');
        }
    }


    /**
     * Create new password
     *
     * @param array $credentials
     * @return void
     * @throws ApiException
     */
    public function createNewPassword(array $credentials): void
    {
        $status = Password::reset(
            $credentials,
            function ($user) use ($credentials) {
                $user->password = Hash::make($credentials['password']);
                $user->save();
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new ApiException('Invalid token or expired.');
        }
    }


    /**
     * Change password with the current password
     *
     * @param array $credentials
     * @return void
     * @throws UserNotFoundException
     * @throws ValidationException
     */
    public function changePassword(array $credentials): void
    {
        $user = $this->userService->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        // Check request password with user password that saved in database
        // WpPassword can check password if it from WordPress or Laravel password
        if (!WpPassword::check($credentials['currentPassword'], $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => 'The current password is incorrect.'
            ]);
        }

        $user->password = Hash::make($credentials['password']);
        $user->save();
        $user->tokens()->delete();
    }
}
