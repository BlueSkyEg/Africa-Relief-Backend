<?php

use App\Http\Controllers\Stripe\PaymentMethodController;
use App\Http\Controllers\Stripe\SingleChargeController;
use App\Http\Controllers\Stripe\SubscriptionController;
use App\Http\Controllers\Stripe\WebhookController;
use Illuminate\Support\Facades\Route;



Route::controller(PaymentMethodController::class)->middleware('auth:sanctum')->prefix('user/payment-method')->group(function () {
    Route::post('/save', 'savePaymentMethod');
    Route::put('/update', 'updatePaymentMethod');
    Route::get('/retrieve/all', 'retrieveAllPaymentMethods');
    Route::get('/retrieve/{paymentMethodId}', 'retrievePaymentMethod');
    Route::delete('/delete/{paymentMethodId}', 'deletePaymentMethod');
});

Route::get('/payment-method/setup-intent', [PaymentMethodController::class, 'setupPaymentMethodIntent']);
Route::post('/create-single-charge', [SingleChargeController::class, 'createSingleCharge']);

Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
    Route::post('/create', [SubscriptionController::class, 'createSubscription']);
});

Route::post('/stripe-webhook', [WebhookController::class, 'listenStripeWebhook']);
