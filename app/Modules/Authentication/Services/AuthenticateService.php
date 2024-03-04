<?php

namespace App\Modules\Authentication\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Modules\User\Services\GetUserService;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthenticateService
{
    public function __construct(private GetUserService $getUserService)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->getUserService->getUserByEmail($request->email);

        if (! ($user && WpPassword::check($request->password, $user?->password))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(true, 'User logged successfully', ['user' => $user, 'accessToken' => $token]);
    }
}
