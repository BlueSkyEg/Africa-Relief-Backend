<?php

use App\Http\Controllers\Stripe\CustomerController;
use App\Http\Controllers\Stripe\PaymentMethodController;
use App\Http\Controllers\Stripe\SingleChargeController;
use App\Http\Controllers\Stripe\SubscriptionController;
use App\Http\Controllers\Stripe\WebhookController;
use Illuminate\Support\Facades\Route;

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

Route::controller(CustomerController::class)->prefix('customer')->group(function () {
    Route::post('/create', 'createCustomer');
});

Route::controller(PaymentMethodController::class)->prefix('payment-method')->group(function () {
    Route::get('/setup-intent', 'setupPaymentMethodIntent');
    Route::post('/save', 'savePaymentMethod');
    Route::put('/update', 'updatePaymentMethod');
    Route::get('/retrieve/all', 'retrieveAllPaymentMethods');
    Route::get('/retrieve/{paymentMethodId}', 'retrievePaymentMethod');
    Route::put('/default', 'updateDefaultPaymentMethod');
    Route::delete('/delete/{paymentMethodId}', 'deletePaymentMethod');
});

Route::controller(SingleChargeController::class)->prefix('single-charge')->group(function () {
    Route::post('/create', 'createSingleCharge');
});

Route::controller(SubscriptionController::class)->prefix('subscription')->group(function () {
    Route::post('/create', 'createSubscription');
    Route::post('/cancel', 'cancelSubscription');
    Route::get('/retrieve/{subscriptionId}', 'retrieveSubscription');
});

Route::post('/stripe-webhook', [WebhookController::class, 'listenStripeWebhook']);
