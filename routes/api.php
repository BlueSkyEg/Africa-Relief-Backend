<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\QuickBooks\QuickBooksController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
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

// Authentication
require __DIR__ . '/api/auth.php';

// Stripe
require __DIR__ . '/api/stripe.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getAuthUser']);
    Route::get('/user/donations', [DonationController::class, 'getUserDonations']);
    Route::get('/user/subscriptions', [SubscriptionController::class, 'getUserSubscriptions']);
    Route::Delete('/user/subscription', [SubscriptionController::class, 'cancelUserSubscription']);
    Route::put('/user/info', [UserController::class, 'updateUserInfo']);
    Route::post('/user/img', [UserController::class, 'updateUserImage']);
});

Route::get('/quickbooks/{entity}', [QuickBooksController::class, 'getTransactions']);
//Route::get('/quickbooks/authorize', [QuickBooksController::class, 'authorizeQB'])->name('quickbooks.authorize');
//Route::get('/quickbooks/callback', [QuickBooksController::class, 'handleCallback'])->name('quickbooks.callback');
