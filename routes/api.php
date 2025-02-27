<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;

// V1
require __DIR__ . '/api/V1/api-v1.php';

// V2
Route::prefix('v2')->group(function () {
    require __DIR__ . '/api/V2/api-v2.php';
});
