<?php

namespace App\Http\Controllers\Authentication;

use App\Mail\Auth\WelcomeMail;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\User\Authentication\WPPassValidationService;
use Illuminate\Support\Facades\Mail;
use App\Services\Donor\DonorService;

class RegisterController extends Controller
{
    public function __construct(
        protected WPPassValidationService $WPPassValidationService,
        protected DonorService $donorService,
    ) {
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $this->WPPassValidationService->hashPassword($request->password),
            ]);
            $this->donorService->registerNewDonor($user);

            // // Send mail
            // Mail::to($user->email)->send(new WelcomeMail(["user" => $user]));

            return $this->successResponse("registerd successfully", [
                "user"  =>  new UserResource($user),
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('could not create token ' . $e->getMessage());
        }
    }
}
