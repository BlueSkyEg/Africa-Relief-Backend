<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                $errors = [
                    'email' => ['provided credentials are incorrect.'],
                ];
                return $this->validationResponse($errors);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('could not create token');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Return success response with user and token
        return $this->successResponse("login successfully. welcome!", [
            "user"  =>  new UserResource($user),
            "token" => $token,
        ]);
    }
}
