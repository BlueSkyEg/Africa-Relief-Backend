<?php

use App\Http\Controllers\V1\QuickBooksController;
use Illuminate\Support\Facades\Route;

Route::prefix('quickbooks')->controller(QuickBooksController::class)->group(function () {
    Route::get('/authorize', 'sendAuthorizationMail');
    Route::get('/callback', 'handleCallbackAfterAuthorization');
    Route::get('/sync/sales-receipts', 'syncSalesReceiptsFromQuickbooks');
    Route::get('/sync/invoices', 'syncInvoicesFromQuickbooks');
    Route::get('/sync/payments', 'syncPaymentFromQuickbooks');
    Route::get('/entity/{entity}', 'getEntityFromQuickbooks');
});
