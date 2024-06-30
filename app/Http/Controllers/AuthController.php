<?php

namespace App\Http\Controllers;

use App\Modules\Authentication\Requests\ChangePasswordRequest;
use App\Modules\Authentication\Requests\LoginRequest;
use App\Modules\Authentication\Requests\NewPasswordRequest;
use App\Modules\Authentication\Requests\RegisterRequest;
use App\Modules\Authentication\Requests\SendPasswordResetLinkRequest;
use App\Modules\Authentication\Services\AuthenticateService;
use App\Modules\Authentication\Services\EmailVerificationService;
use App\Modules\Authentication\Services\PasswordService;
use App\Modules\User\Resources\UserResource;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateService $authenticateService,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly PasswordService $passwordService
    )
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $userData = $this->authenticateService->login($request->validated());

        return response()->api(true, 'User logged in successfully.', [
            'user' => new UserResource($userData['user']),
            'accessToken' => $userData['accessToken'],
            'tokenExpiresAt' => $userData['tokenExpiresAt']
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->authenticateService->logout();

        return response()->api(true, 'user logged out successfully');
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $this->authenticateService->register($request->validated());

        return response()->api(true, 'User created successfully', [
            'user' => new UserResource($userData['user']),
            'accessToken' => $userData['accessToken'],
            'tokenExpiresAt' => $userData['tokenExpiresAt']
        ]);
    }

    public function resendEmailVerificationNotification(): JsonResponse
    {
        $this->emailVerificationService->resendEmailVerificationNotification();

        return response()->api(true, 'The email verification link sent successfully');
    }

    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $this->emailVerificationService->verifyEmail();

        return response()->api(true, 'The email verified successfully.');
    }

    public function sendPasswordResetLink(SendPasswordResetLinkRequest $request): JsonResponse
    {
        $this->passwordService->sendPasswordResetLink($request->only('email'));

        return response()->api(true, 'Password reset link sent successfully.');
    }

    public function createNewPassword(NewPasswordRequest $request): JsonResponse
    {
        $this->passwordService->createNewPassword($request->only('email', 'password', 'password_confirmation', 'token'));

        return response()->api(true, 'New password created successfully.');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->passwordService->changePassword($request->validated());

        return response()->api(true, 'Password changed successfully.');
    }
}
