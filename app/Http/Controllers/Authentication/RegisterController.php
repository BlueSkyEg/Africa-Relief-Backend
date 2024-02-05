<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator->errors()->toArray());
        }

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return $this->successResponse("registerd successfully", [
                "user"  =>  new UserResource($user),
                "token" => $token,
            ], 201);

        } catch (JWTException $e) {
            return $this->errorResponse('could not create token');
        }
    }
}
