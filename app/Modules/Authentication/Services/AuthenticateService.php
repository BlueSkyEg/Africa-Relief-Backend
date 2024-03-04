<?php

namespace App\Modules\Authentication\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Modules\User\Services\GetUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateService
{
    public function __construct(
        private GetUserService $getUserService
    )
    {
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = $this->getUserService->getUserByEmail($request->email);
        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(true, 'User logged successfully', ['user' => $user, 'accessToken' => $token]);
    }
}
