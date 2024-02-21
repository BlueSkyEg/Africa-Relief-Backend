<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('authed')->prefix('user')->group(function () {

    // Profile
    Route::get('/', [UserController::class, 'show'])->name('profile.show');
    Route::post('/', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/', [UserController::class, 'destroy'])->name('profile.destroy');

    // Cancel Subscription
    Route::post('/subscriptions/{subscription}', [UserController::class, 'cancelSubscription'])->name('subscription.cancel');
});
