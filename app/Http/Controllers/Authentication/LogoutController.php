<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse("logged out successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('failed to logout');
        }
    }
}
