<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Modules\Authentication\Services\AuthenticateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticateService $authenticateService
    )
    {
    }

    public function login(LoginRequest $request)
    {
        return $this->authenticateService->login($request);
    }

    public function logout()
    {

    }

    public function register()
    {

    }

    public function sendEmailVerificationNotification()
    {

    }

    public function verifyEmail()
    {

    }

    public function SendPasswordResetLink()
    {

    }

    public function createNewPassword()
    {

    }
}
