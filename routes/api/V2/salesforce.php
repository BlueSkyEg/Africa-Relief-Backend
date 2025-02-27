<?php


use App\Http\Controllers\V2\SalesforceController;
use Illuminate\Support\Facades\Route;

Route::prefix('salesforce')->controller(SalesforceController::class)->group(function () {
    Route::post('/sync/sales-receipts', 'syncAllQuickbooksReceiptsToSalesforce');
    Route::post('/sync/invoices', 'syncAllQuickbooksInvoicesToSalesforce');
    Route::post('/sync/payments', 'syncAllQuickbooksPaymentsToSalesforce');
});
