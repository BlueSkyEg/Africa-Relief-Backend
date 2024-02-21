<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Subscription;

class UserController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::user();
    }
    public function show()
    {
        return $this->successResponse("user retreived successfully", new UserResource($this->user));
    }

    public function update(UpdateProfileRequest $request)
    {
        // Update the profile with the validated data
        $this->user->update($request->all());

        // Return a success response
        return $this->successResponse("profile info updated successfully", new UserResource($this->user), 201);
    }

    public function destroy()
    {
        // Delete the profile
        $this->user->delete();

        // Return a success response
        return $this->successResponse("profile deleted successfully");
    }

    public function cancelSubscription(Subscription $subscription)
    {
        // Check if the authenticated user owns the subscription
        if ($subscription->user_id !== $this->user->id) {
            return $this->errorResponse('you are not authorized to cancel this subscription', 403);
        }

        // Update the subscription status to "cancelled"
        $subscription->status = 'cancelled';
        $subscription->save();
        return $this->successResponse('subscription cancelled successfully', 200);
    }
}
