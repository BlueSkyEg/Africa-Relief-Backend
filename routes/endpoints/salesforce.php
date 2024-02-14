<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Salesforce\AuthController;

Route::prefix('salesforce')->group(function () {

    // Route::get('/accounts', [SalesforceController::class, 'getAccounts']);

    Route::prefix('auth')->group(function () {
        Route::get('/jwt', [AuthController::class, 'generateJwtToken']);
        Route::get('/user-pass-flow', [AuthController::class, 'userPassFlow']);
    });

});
