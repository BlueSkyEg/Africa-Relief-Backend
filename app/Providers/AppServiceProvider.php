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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Stripe::setApiKey(config('stripe.secret_key'));

        Response::macro('success', function (string $message, $data = null) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => $data,
                'errors' => null
            ]);
        });

        Response::macro('pagination', function (string $message, $data, $pagination) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'data' => $data,
                    'pagination' => [
                        'current_page' => $pagination->currentPage(),
                        'per_page' => $pagination->perPage(),
                        'total' => $pagination->total(),
                        'last_page' => $pagination->lastPage()
                    ]
                ],
                'errors' => null
            ]);
        });

        Response::macro('error', function (string $message, $errors = null, $status = 200) {
            return Response::json([
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => $errors
            ], $status);
        });

        Response::macro('validationError', function ($errors) {
            return Response::json([
                'success' => false,
                'message' => 'validation error',
                'data' => null,
                'errors' => $errors
            ]);
        });
    }
}
