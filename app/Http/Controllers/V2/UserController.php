<?php

namespace App\Http\Controllers\V2;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V2\UpdateUserImageRequest;
use App\Http\Requests\V2\UpdateUserInfoRequest;
use App\Http\Resources\V2\DonationResource;
use App\Http\Resources\V2\SubscriptionResource;
use App\Http\Resources\V2\UserResource;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * @param UserService $userService
     */
    public function __construct(private readonly UserService $userService)
    {
    }


    /**
     * @return JsonResponse
     */
    public function getAuthUser(): JsonResponse
    {
        $user = $this->userService->getAuthUser();

        return response()->success('User retrieved successfully.', new UserResource($user));
    }


    /**
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function getUserDonations(): JsonResponse
    {
        $donations = $this->userService->getUserDonations();

        return response()->success('Donations retrieved successfully', DonationResource::collection($donations));
    }


    /**
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function getUserSubscriptions(): JsonResponse
    {
        $subscriptions = $this->userService->getUserSubscriptions();

        return response()->success('Donations retrieved successfully', SubscriptionResource::collection($subscriptions));
    }


    /**
     * @param UpdateUserInfoRequest $request
     * @return JsonResponse
     * @throws ApiException
     * @throws UserNotFoundException
     */
    public function updateUserInfo(UpdateUserInfoRequest $request): JsonResponse
    {
        $user = $this->userService->updateUserInfo($request->validated());

        return response()->success('User updated successfully.', new UserResource($user));
    }


    /**
     * @param UpdateUserImageRequest $request
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function updateUserImage(UpdateUserImageRequest $request): JsonResponse
    {
        $user = $this->userService->updateUserImage($request->file('img'));

        return response()->success('User image updated successfully.', new UserResource($user));
    }


    /**
     * @return JsonResponse
     * @throws UserNotFoundException
     */
    public function deleteUser(): JsonResponse
    {
        $this->userService->deleteUser();

        return response()->success('User deleted successfully.');
    }
}
