<?php

namespace App\Modules\Authentication\Services;

use App\Exceptions\ApiException;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Services\UserService;
use Illuminate\Auth\Events\Verified;

class EmailVerificationService
{
    public function __construct(private readonly UserService $getUserService)
    {
    }


    /**
     * Send a new email verification notification.
     *
     * @return void
     * @throws ApiException|UserNotFoundException
     */
    public function resendEmailVerificationNotification(): void
    {
        $user = $this->getUserService->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        // check if the user already verified his email
        if ($user->hasVerifiedEmail()) {
            throw new ApiException('The email already verified.');
        }

        $user->sendEmailVerificationNotification();
    }


    /**
     * Mark the authenticated user's email address as verified.
     *
     * @return void
     * @throws ApiException|UserNotFoundException
     */
    public function verifyEmail(): void
    {
        $user = $this->getUserService->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        // check if the user already verified his email
        if ($user->hasVerifiedEmail()) {
            throw new ApiException('The email already verified');
        }

        // Mark email as verified if didn't
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }
}
