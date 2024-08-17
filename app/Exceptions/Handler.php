<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport = [
        CustomException::class
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException && $request->expectsJson()) {
            return response()->validationError($e->errors());
        }

        if ($e instanceof AuthenticationException && $request->expectsJson() && $request->is('api/*')) {
            return response()->error('Unauthorized', null, 401);
        }

        if ($e instanceof CustomException) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }

        Log::debug('An unexpected error occurred: ' . $e->getMessage() . ' With status code: ' . $e->getCode());
        return response()->error('An unexpected error occurred.', null, 500);

        //return parent::render($request, $e);
    }
}
