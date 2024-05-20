<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\Stripe\StripePaymentController;
use App\Http\Controllers\Stripe\StripeWebhookController;
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

// Posts
require __DIR__ . '/api/posts.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getAuthUser']);
    Route::get('/user/donations', [DonationController::class, 'getUserDonations']);
    Route::get('/user/subscriptions', [SubscriptionController::class, 'getUserSubscriptions']);
    Route::Delete('/user/subscriptions/{subscriptionId}', [StripePaymentController::class, 'cancelStripeSubscription']);
    Route::put('/user/info', [UserController::class, 'updateUserInfo']);
    Route::post('/user/img', [UserController::class, 'updateUserImage']);
    Route::Delete('/user', [UserController::class, 'deleteUser']);
});

Route::prefix('webhook')->group(function () {
    Route::post('/stripe', [StripeWebhookController::class, 'triggerStripeWebhook']);
});

//Route::get('/quickbooks/authorize', [QuickBooksController::class, 'getAuthorizationUrl']);
//Route::get('/quickbooks/callback', [QuickBooksController::class, 'handleCallback']);
//Route::get('/quickbooks/{entity}', [QuickBooksController::class, 'getTransactions']);

