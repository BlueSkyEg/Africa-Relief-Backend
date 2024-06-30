<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiResponseException extends Exception
{
    public function render() {
        return response()->api(false, $this->getMessage());
    }
}
