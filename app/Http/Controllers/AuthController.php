<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Modules\Authentication\Services\AuthenticateService;
use App\Modules\Authentication\Services\EmailVerificationService;
use App\Modules\Authentication\Services\PasswordService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

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
        return $this->emailVerificationService->sendNewEmailVerification($request);
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
