<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\User\UpdateUserImageRequest;
use App\Modules\User\Services\GetUserService;
use App\Modules\User\Services\UpdateUserService;
use App\Http\Requests\User\UpdateUserInfoRequest;

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
        $user = $request->user();
        $imagePath = $request->file('img')->store('users/images');
        Storage::delete('users/images/'.$user->img);
        $user->img = Str::afterLast($imagePath, '/');
        $user->save();

        return response()->api(true, 'user image updated successfully', $user);
    }
}
