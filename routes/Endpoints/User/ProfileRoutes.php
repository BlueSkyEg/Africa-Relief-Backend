<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProfileController;

Route::middleware('authed')->prefix('user')->group(function () {

    // Profile
    Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cancel Subscription
    Route::post('/subscriptions/{subscription}', [ProfileController::class, 'cancelSubscription'])->name('subscription.cancel');
});
