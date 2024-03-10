<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Modules\User\Services\GetUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private GetUserService $getUserService)
    {
    }

    public function getAuthUser()
    {
        $user = $this->getUserService->getAuthUser();

        return response()->api(true, 'user retrieved successfully', $user);
    }
}
