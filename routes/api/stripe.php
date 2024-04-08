<?php

use App\Http\Controllers\Stripe\StripePaymentController;
use App\Http\Controllers\Stripe\StripePaymentMethodController;
use App\Http\Controllers\Stripe\StripeWebhookController;
use Illuminate\Support\Facades\Route;



Route::controller(StripePaymentMethodController::class)->middleware('auth:sanctum')->prefix('user/payment-methods')->group(function () {
    Route::get('', 'listPaymentMethods');
    Route::get('/{paymentMethodId}', 'getPaymentMethod');
    Route::post('/save', 'attachPaymentMethod');
    Route::delete('/{paymentMethodId}', 'deletePaymentMethod');
});

Route::controller(StripePaymentController::class)->prefix('payment')->group(function () {
    Route::post('', 'createStripePayment');
    Route::get('/setup-intent', 'setupStripeIntent');
});
