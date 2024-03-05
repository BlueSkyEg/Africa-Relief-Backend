<?php

namespace App\Modules\Authentication\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Modules\User\Services\CreateUserService;
use App\Modules\User\Services\GetUserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthenticateService
{
    public function __construct(private GetUserService $getUserService, private CreateUserService $createUserService)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->getUserService->getUserByEmailOrUsername($request->email);

        if (! ($user && WpPassword::check($request->password, $user?->password))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(true, 'User logged in successfully', ['user' => $user, 'accessToken' => $token]);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->createUserService->createUser($request);

        // Send email verification
        event(new Registered($user));

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(true, 'User created successfully', ['user' => $user, 'accessToken' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->api(true, 'user logged out successfully');
    }

    public function logoutFromAllDevices(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->api(true, 'user logged out successfully from all devices');
    }
}
