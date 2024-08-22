<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Modules\DonationCore\Donation\Resources\DonationResource;
use App\Modules\DonationCore\Subscription\Resources\SubscriptionResource;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Requests\UpdateUserImageRequest;
use App\Modules\User\Requests\UpdateUserInfoRequest;
use App\Modules\User\Resources\UserResource;
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
