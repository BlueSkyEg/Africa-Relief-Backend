<?php

namespace App\Modules\Authentication\Services;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Modules\User\Services\UpdateUserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class PasswordService
{
    public function __construct(private UpdateUserService $updateUserService)
    {
    }

    // Send password reset link request
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => $status,
            ]);
        }

        return response()->api(true, $status);
    }

    // Create new password
    public function createNewPassword(NewPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user = $this->updateUserService->updateUserPassword($user, $request->password);
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => $status
            ]);
        }

        return response()->api(true, $status);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        if (! WpPassword::check($request->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => 'The old password is incorrect.'
            ]);
        }

        $user = $this->updateUserService->updateUserPassword($user, $request->password);
        $user->tokens()->delete();

        return response()->api(true, 'password changed successfully');
    }
}
