<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Authentication\LogoutController;
use App\Http\Controllers\Authentication\PasswordResetController;
use App\Http\Controllers\Authentication\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('forget-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::middleware('authed')->group(function () {
        Route::get('assigned-user', [AuthController::class, 'assignedUser']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('logout', [LogoutController::class, 'logout']);
    });
});
