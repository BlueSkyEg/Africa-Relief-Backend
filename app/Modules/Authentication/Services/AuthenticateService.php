<?php

namespace App\Modules\Authentication\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Modules\User\Services\CreateUserService;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthenticateService
{
    public function __construct(private GetUserService $getUserService, private CreateUserService $createUserService, private UpdateUserService $updateUserService)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->getUserService->getUserByEmailOrUsername($request->email);

        try {
            if ($user && WpPassword::check($request->password, $user?->password)) {
                $this->updateUserService->updateUserPassword($user, $request->password);
            } else if (!Auth::attempt($request->validated())) {
                throw ValidationException::withMessages([
                    'email' => 'Invalid credentials',
                ]);
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        $tokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(
            true,
            'User logged in successfully',
            [
                'user' => $user,
                'accessToken' => $token,
                'tokenExpiresAt' => $tokenExpiresAt->getTimestampMs()
            ]
        );
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->createUserService->createUser($request);

        // Send email verification
        event(new Registered($user));

        $tokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->api(
            true,
            'User created successfully',
            [
                'user' => $user,
                'accessToken' => $token,
                'tokenExpiresAt' => $tokenExpiresAt->getTimestampMs()
            ]
        );
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
