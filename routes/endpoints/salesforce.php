<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Salesforce\SalesforceController;

Route::prefix('salesforce')->group(function () {

    Route::get('/accounts', [SalesforceController::class, 'getAccounts']);
    Route::get('/get-jwt-key', [SalesforceController::class, 'generateJwtToken']);
});
