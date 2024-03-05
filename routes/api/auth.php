<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/send-password-reset-link', 'sendPasswordResetLink');
    Route::post('/create-new-password', 'createNewPassword');
});

Route::controller(AuthController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'logout');
    Route::post('/logout-all-devices', 'logoutFromAllDevices');
    Route::post('/send-new-email-verification', 'sendEmailVerificationNotification');
    Route::post('/change-password', 'changePassword');
});
