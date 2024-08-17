<?php

namespace App\Modules\User\Exceptions;

use App\Exceptions\CustomException;

class UserNotFoundException extends CustomException
{
    protected $message = 'User not found.';
    protected $code = 404;
}
