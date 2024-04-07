<?php

namespace App\Http\Controllers;

use App\Modules\Authentication\Requests\ChangePasswordRequest;
use App\Modules\Authentication\Requests\LoginRequest;
use App\Modules\Authentication\Requests\NewPasswordRequest;
use App\Modules\Authentication\Requests\RegisterRequest;
use App\Modules\Authentication\Services\AuthenticateService;
use App\Modules\Authentication\Services\EmailVerificationService;
use App\Modules\Authentication\Services\PasswordService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticateService $authenticateService,
        private EmailVerificationService $emailVerificationService,
        private PasswordService $passwordService
    )
    {
    }

    public function login(LoginRequest $request)
    {
        return $this->authenticateService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authenticateService->logout($request);
    }

    public function logoutFromAllDevices(Request $request)
    {
        return $this->authenticateService->logoutFromAllDevices($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->authenticateService->register($request);
    }

    // Send a new email verification notification.
    public function sendEmailVerificationNotification(Request $request)
    {
        return $this->emailVerificationService->resendEmailVerificationNotification($request);
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        return $this->emailVerificationService->verifyEmail($request);
    }

    public function sendPasswordResetLink(Request $request)
    {
        return $this->passwordService->sendPasswordResetLink($request);
    }

    public function createNewPassword(NewPasswordRequest $request)
    {
        return $this->passwordService->createNewPassword($request);
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->passwordService->changePassword($request);
    }
}
