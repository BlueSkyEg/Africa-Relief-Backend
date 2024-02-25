<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stripe\TestController;

Route::group(['prefix' => 'test'], function () {
    Route::get('/create-payment-method', [TestController::class, 'createPaymentMethod']);
});