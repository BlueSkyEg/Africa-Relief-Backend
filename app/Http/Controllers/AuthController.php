<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Modules\Authentication\Requests\ChangePasswordRequest;
use App\Modules\Authentication\Requests\LoginRequest;
use App\Modules\Authentication\Requests\NewPasswordRequest;
use App\Modules\Authentication\Requests\RegisterRequest;
use App\Modules\Authentication\Requests\SendPasswordResetLinkRequest;
use App\Modules\Authentication\Services\AuthenticateService;
use App\Modules\Authentication\Services\EmailVerificationService;
use App\Modules\Authentication\Services\PasswordService;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Resources\UserResource;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateService      $authenticateService,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly PasswordService          $passwordService
    )
    {
    }


    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $userData = $this->authenticateService->login($request->validated());

        return response()->success('User logged in successfully.', $userData);
    }


    /**
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function logout(): JsonResponse
    {
        $this->authenticateService->logout();

        return response()->success('User logged out successfully.');
    }


    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $this->authenticateService->register($request->validated());

        return response()->success('User created successfully', $userData);
    }


    /**
     * @return JsonResponse
     * @throws ApiException
     * @throws UserNotFoundException
     */
    public function resendEmailVerificationNotification(): JsonResponse
    {
        $this->emailVerificationService->resendEmailVerificationNotification();

        return response()->success('The email verification link sent successfully');
    }


    /**
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     * @throws ApiException
     * @throws UserNotFoundException
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        $this->emailVerificationService->verifyEmail();

        return response()->success('The email verified successfully.');
    }


    /**
     * @param SendPasswordResetLinkRequest $request
     * @return mixed
     * @throws ApiException
     */
    public function sendPasswordResetLink(SendPasswordResetLinkRequest $request)
    {
        $this->passwordService->sendPasswordResetLink($request->only('email'));

        return response()->success('Password reset link sent successfully.');
    }


    /**
     * @param NewPasswordRequest $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function createNewPassword(NewPasswordRequest $request): JsonResponse
    {
        $this->passwordService->createNewPassword($request->only('email', 'password', 'password_confirmation', 'token'));

        return response()->success('New password created successfully.');
    }


    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     * @throws UserNotFoundException
     * @throws ValidationException
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->passwordService->changePassword($request->validated());

        return response()->success('Password changed successfully.');
    }
}
