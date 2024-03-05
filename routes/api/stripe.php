<?php

use App\Http\Controllers\Stripe\PaymentMethodController;
use App\Http\Controllers\Stripe\SingleChargeController;
use App\Http\Controllers\Stripe\SubscriptionController;
use App\Http\Controllers\Stripe\WebhookController;
use Illuminate\Support\Facades\Route;


Route::controller(PaymentMethodController::class)->prefix('payment-method')->group(function () {
    Route::get('/setup-intent', 'setupPaymentMethodIntent');
    Route::post('/save', 'savePaymentMethod');
    Route::put('/update', 'updatePaymentMethod');
    Route::get('/retrieve/all', 'retrieveAllPaymentMethods');
    Route::get('/retrieve/{paymentMethodId}', 'retrievePaymentMethod');
    Route::delete('/delete/{paymentMethodId}', 'deletePaymentMethod');
});

Route::post('/create-single-charge', [SingleChargeController::class, 'createSingleCharge']);

Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
    Route::post('/create-subscription', [SubscriptionController::class, 'createSubscription']);
    Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription']);
});

Route::post('/stripe-webhook', [WebhookController::class, 'listenStripeWebhook']);
