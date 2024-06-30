<?php

namespace App\Http\Controllers;

use App\Modules\User\Requests\UpdateUserImageRequest;
use App\Modules\User\Requests\UpdateUserInfoRequest;
use App\Modules\User\Resources\UserResource;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly GetUserService $getUserService,
        private readonly UpdateUserService $updateUserService
    )
    {
    }

    public function getAuthUser(): JsonResponse
    {
        $user = $this->getUserService->getAuthUser();

        return response()->api(true, 'User retrieved successfully.', new UserResource($user));
    }

    public function updateUserInfo(UpdateUserInfoRequest $request): JsonResponse
    {
        $this->updateUserService->updateUserInfo($request->validated());

        return response()->api(true, 'User updated successfully.');
    }

    public function updateUserImage(UpdateUserImageRequest $request): JsonResponse
    {
        $user = $this->updateUserService->updateUserImage($request->file('img'));

        return response()->api(true, 'User image updated successfully.', new UserResource($user));
    }

    public function deactivateUser(): JsonResponse
    {
        $this->updateUserService->deactivateUser();

        return response()->api(true, 'User deleted successfully.');
    }
}
