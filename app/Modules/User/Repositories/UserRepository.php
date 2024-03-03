<?php

namespace App\Modules\User\Repositories;

class UserRepository
{
    public function getAuthUser()
    {
        return auth('sanctum')->user();
    }
}
