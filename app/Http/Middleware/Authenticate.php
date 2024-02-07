<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Authenticate
{
    use ApiResponse;
    public function handle($request, Closure $next)
    {
        /*==================================================================
        =================== JWT VALIDATION SECTION =========================
        ==================================================================*/
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return $this->errorResponse("token not provided", 401);
            }
            // Validate the token
            JWTAuth::setToken($token)->getPayload();
        } catch (TokenInvalidException $e) {
            return $this->errorResponse("invalid token", 401);
        } catch (TokenExpiredException $e) {
            // Check if not request is for the refresh token route
            if (!$request->is('auth/refresh-token')) {
                return $this->errorResponse("token has expired", 401);
            }
        }

        return $next($request);
    }
}
