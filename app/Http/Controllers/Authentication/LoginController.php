<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Authentication\WPPassValidationService;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function __construct(protected WPPassValidationService $WPPassValidationService)
    {
    }
    public function login(LoginRequest $request)
    {
        // Check if the user is a Client
        if ($user = User::where('email', $request->email)->first()) {
            try {
                // Validate password using WordPress's hashing method
                $passwordMatches = $this->WPPassValidationService->validatePassword($request->password, $user->password);

                // If user not found or password doesn't match, return error
                if (!$passwordMatches) {
                    $errors = [
                        'email' => ['invalid credentials.'],
                    ];
                    return $this->validationResponse($errors);
                }

                // Generate JWT token for the user
                $token = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                return $this->errorResponse('could not create token.');
            }

            // Return success response with user and token
            return $this->successResponse("login successfully. welcome!", [
                "user"  =>  new UserResource($user),
                "token" => $token,
            ]);
        } else {
            $errors = [
                "email" => ["invalid credentials."],
            ];
            return $this->validationResponse($errors);
        }
    }
}
