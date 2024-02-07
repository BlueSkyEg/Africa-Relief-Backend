<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use App\Http\Requests\Auth\RestPasswordRequest;
use App\Http\Requests\Auth\SendPasswordRestLinkMail;
use App\Services\Authentication\WPPassValidationService;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct(protected WPPassValidationService $WPPassValidationService)
    {
    }
    public function forgotPassword(SendPasswordRestLinkMail $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            // Credential Not Matched With Our
            if ($user == null) {

                $errors = [
                    "email" => ["this email does't exist"],
                ];
                return $this->validationResponse($errors);
            } else {
                // Update the user's remember_token
                $user->setRememberToken(Str::random(10));
                $user->save();

                $mailData = [
                    "user" => $user,
                    "resetUrl" => env("APP_URL") . 'reset-password/?remember_token=' . $user->rememberToken,
                ];

                // Send mail
                Mail::to($user->email)->send(new PasswordResetMail($mailData));

                return $this->successResponse("email reset password link sent successfully");
            }
        } catch (\Exception $e) {
            return $this->errorResponse("server error: " . $e->getMessage());
        }
    }

    public function resetPassword(RestPasswordRequest $request)
    {
        try {

            if (!$request->remember_token || $request->remember_token == '') {
                return $this->errorResponse("no remember token provided", 400);
            }

            $user = User::where('remember_token', $request->remember_token)->first();

            if ($user) {
                // Update the User Row
                $user["password"]  = $this->WPPassValidationService->hashPassword($request->password);
                $user["remember_token"] = null;
                $user->save();

                return $this->successResponse("password updated successfully");
            } else {
                return $this->errorResponse("user not found", 400);
            }
        } catch (\Exception $e) {
            return $this->errorResponse("server error: " . $e->getMessage());
        }
    }
}
