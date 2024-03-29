<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Authentication\LoginController;
use App\Http\Controllers\User\Authentication\RegisterController;
use App\Http\Controllers\User\Authentication\LogoutController;
use App\Http\Controllers\User\Authentication\PasswordResetController;
use App\Http\Controllers\User\Authentication\AuthController;

Route::prefix('auth')->group(function () {

    // Register
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    // Reset Password Endpoints
    Route::post('forget-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::middleware('authed')->group(function () {
        Route::get('assigned-user', [AuthController::class, 'assignedUser']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('logout', [LogoutController::class, 'logout']);
    });
});
