<?php

namespace App\Http\Controllers;

use App\Modules\User\Requests\UpdateUserImageRequest;
use App\Modules\User\Requests\UpdateUserInfoRequest;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(private GetUserService $getUserService, private UpdateUserService $updateUserService)
    {
    }

    public function getAuthUser()
    {
        $user = $this->getUserService->getAuthUser();

        return response()->api(true, 'user retrieved successfully', $user);
    }

    public function updateUserInfo(UpdateUserInfoRequest $request)
    {
        return $this->updateUserService->updateUserInfo($request);
    }

    public function updateUserImage(UpdateUserImageRequest $request)
    {
        return $this->updateUserService->updateUserImage($request);
    }

    public function deleteUser(Request $request)
    {
        $user = $request->user();
        $user->active = '0';
        $user->save();
        $user->tokens()->delete();

        return response()->api(true, 'user deleted successfully');
    }
}
