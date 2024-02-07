<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;

class ProfileController extends Controller
{

    public function show()
    {
        $user = JWTAuth::user();
        return $this->successResponse("user retreived successfully", new UserResource($user));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = JWTAuth::user();

        // Update the profile with the validated data
        $user->update($request->all());

        // Return a success response
        return $this->successResponse("profile info updated successfully", new UserResource($user), 201);
    }

    public function destroy()
    {
        $user = JWTAuth::user();

        // Delete the profile
        $user->delete();

        // Return a success response
        return $this->successResponse("profile deleted successfully");
    }
}
