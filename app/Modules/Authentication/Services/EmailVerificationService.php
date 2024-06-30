<?php

namespace App\Modules\Authentication\Services;

use App\Exceptions\ApiResponseException;
use App\Modules\User\Services\GetUserService;
use Illuminate\Auth\Events\Verified;

class EmailVerificationService
{
    public function __construct(private readonly GetUserService $getUserService)
    {
    }

    // Send a new email verification notification.
    public function resendEmailVerificationNotification(): void
    {
        $user = $this->getUserService->getAuthUser();

        // check if the user already verified his email
        if ($user->hasVerifiedEmail()) {
            throw new ApiResponseException('The email already verified.');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while resending email verification: ' . $e->getMessage());
        }
    }

    // Mark the authenticated user's email address as verified.
    public function verifyEmail(): void
    {
        $user = $this->getUserService->getAuthUser();

        // check if the user already verified his email
        if ($user->hasVerifiedEmail()) {
            throw new ApiResponseException('The email already verified');
        }

        // Mark email as verified if didn't
        try {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred while resending email verification: ' . $e->getMessage());
        }
    }
}
