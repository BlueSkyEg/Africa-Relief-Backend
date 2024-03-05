<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

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
        Stripe::setApiKey(env('STRIPE_SECRET'));

        Response::macro('api', function (bool $success, string $message, $data = null, $errors = null, $status = 200) {
            return Response::json([
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'errors' => $errors
            ], $status);
        });
    }
}
