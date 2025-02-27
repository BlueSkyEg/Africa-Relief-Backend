<?php

use App\Http\Controllers\V2\FirebaseDeviceTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/notifications/device-token', [FirebaseDeviceTokenController::class, 'storeFirebaseDeviceToken']);
