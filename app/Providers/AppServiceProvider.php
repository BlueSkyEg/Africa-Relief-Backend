<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('api', function (bool $success, string $message, $data = null, $errors = null) {
            return Response::json([
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'errors' => $errors
            ]);
        });
    }
}
