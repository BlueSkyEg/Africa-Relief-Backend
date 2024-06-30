<?php

namespace App\Modules\Authentication\Services;

use App\Exceptions\ApiResponseException;
use App\Modules\Donor\Services\GetDonorService;
use App\Modules\Donor\Services\UpdateDonorService;
use App\Modules\User\Services\CreateUserService;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthenticateService
{
    public function __construct(
        private readonly GetUserService     $getUserService,
        private readonly CreateUserService  $createUserService,
        private readonly UpdateUserService  $updateUserService,
        private readonly GetDonorService    $getDonorService,
        private readonly UpdateDonorService $updateDonorService
    )
    {
    }

    public function login(array $credentials): array
    {
        $user = $this->getUserService->getUserByEmailOrUsername($credentials['email']);

        // Validate credentials and active status
        if ($user->active && WpPassword::check($credentials['password'], $user->password)) {
            $this->updateUserService->updateUserPassword($user, $credentials['password']);
        } else if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'active' => '1'])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        try {
            return $this->generateTokenResponse($user);
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred during the login process: ' . $e->getMessage());
        }
    }

    public function register(array $credentials): array
    {
        $user = $this->createUserService->createUser($credentials);

        // Assign user to donor if existing
        $donor = $this->getDonorService->getDonorByEmail($credentials['email']);

        if ($donor) {
            $this->updateDonorService->assignDonorToUser($donor, $user->id);
        }

        try {
            // Send email verification
            event(new Registered($user));

            return $this->generateTokenResponse($user);
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred during the register process: ' . $e->getMessage());
        }
    }

    public function logout(): bool
    {
        $user = $this->getUserService->getAuthUser();

        try {
            return $user->currentAccessToken()->delete();
        } catch (\Exception $e) {
            throw new ApiResponseException('An error occurred during the logout process: ' . $e->getMessage());
        }
    }

    private function generateTokenResponse($user): array
    {
        $token = $user->createToken('apiToken')->plainTextToken;
        $tokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));

        return [
            'user' => $user,
            'accessToken' => $token,
            'tokenExpiresAt' => $tokenExpiresAt->getTimestampMs(),
        ];
    }
}
