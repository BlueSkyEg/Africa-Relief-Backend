<?php

use App\Http\Controllers\FirebaseDeviceTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/notifications/device-token', [FirebaseDeviceTokenController::class, 'storeFirebaseDeviceToken']);
