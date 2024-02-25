<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Salesforce\SalesforceAuthController;

Route::prefix('salesforce')->group(function () {

    Route::get('/get-token', [SalesforceAuthController::class, 'getToken']);
    // Route::get('/transaction/store', [SalesforceController::class, '']);
    // Route::get('/transaction/store', [SalesforceController::class, '']);

});
