<?php

use App\Http\Controllers\DonationCore\StripePaymentController;
use Illuminate\Support\Facades\Route;


Route::controller(StripePaymentController::class)->group(function () {

    Route::prefix('payment')->group(function () {
        Route::post('', 'createStripePayment');
        Route::post('/express-checkout', 'createStripeExpressCheckout');
        Route::get('/setup-intent', 'setupStripeIntent');
    });

    Route::post('/webhook/stripe', 'triggerStripeWebhook');
});
