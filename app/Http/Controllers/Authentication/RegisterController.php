<?php

namespace App\Http\Controllers\Authentication;

use App\Mail\Auth\WelcomeMail;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\Authentication\WPPassValidationService;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct(protected WPPassValidationService $WPPassValidationService)
    {
    }
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $this->WPPassValidationService->hashPassword($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            $mailData = [
                "user" => $user
            ];
            
            // Send mail
            Mail::to($user->email)->send(new WelcomeMail($mailData));

            
            return $this->successResponse("registerd successfully", [
                "user"  =>  new UserResource($user),
                "token" => $token,
            ], 201);
        } catch (JWTException $e) {
            return $this->errorResponse('could not create token');
        }
    }
}
