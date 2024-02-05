<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function assignedUser()
    {
        $user = JWTAuth::user();
        return $this->successResponse("user retreived successfully", new UserResource($user) );
    }

    public function refreshToken()
    {
        try {
            // Attempt to refresh the token
            $token = JWTAuth::parseToken()->refresh();
            return $this->successResponse("token refreshed successfully", ['token' => $token] );
        } catch (\Exception $e) {
            return $this->errorResponse('failed to refresh token');
        }
    }

}
