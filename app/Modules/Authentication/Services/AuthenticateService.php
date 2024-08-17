<?php

namespace App\Modules\Authentication\Services;

use App\Modules\DonationCore\Donor\Services\DonorService;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Resources\UserResource;
use App\Modules\User\Services\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthenticateService
{
    public function __construct(
        private readonly UserService  $userService,
        private readonly DonorService $donorService,
    )
    {
    }


    /**
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        $user = $this->userService->getUserByEmailOrUsername($credentials['email']);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        // Validate credentials and active status
        if ($user->active && WpPassword::check($credentials['password'], $user->password)) {
            $user->password = Hash::make($credentials['password']);
            $user->save();
        } else if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'active' => '1'])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        return $this->generateTokenResponse($user);
    }


    /**
     * @param array $credentials
     * @return array
     */
    public function register(array $credentials): array
    {
        $user = $this->userService->createUser($credentials);

        // Assign user to donor if existing
        $donor = $this->donorService->getDonorByEmail($credentials['email']);

        if ($donor) {
            $donor->user_id = $user->id;
            $donor->save();
        }

        // Send email verification
        event(new Registered($user));

        return $this->generateTokenResponse($user);
    }


    /**
     * @return bool
     * @throws UserNotFoundException
     */
    public function logout(): bool
    {
        $user = $this->userService->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user->currentAccessToken()->delete();
    }


    /**
     * @param $user
     * @return array
     */
    private function generateTokenResponse($user): array
    {
        $token = $user->createToken('apiToken')->plainTextToken;
        $tokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));

        return [
            'user' => new UserResource($user),
            'accessToken' => $token,
            'tokenExpiresAt' => $tokenExpiresAt->getTimestampMs(),
        ];
    }
}
