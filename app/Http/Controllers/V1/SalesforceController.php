<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Modules\Salesforce\Services\SalesforceRecordService;

class SalesforceController extends Controller
{
    public function __construct(private readonly SalesforceRecordService $salesforceRecordService)
    {
    }

    public function syncAllQuickbooksReceiptsToSalesforce()
    {
        $this->salesforceRecordService->syncAllQuickbooksReceiptsToSalesforce();

        return response()->success("QuickBooks receipts had been stored to Salesforce successfully.");
    }

    public function syncAllQuickbooksInvoicesToSalesforce()
    {
        $this->salesforceRecordService->syncAllQuickbooksInvoicesToSalesforce();

        return response()->success("QuickBooks invoices had been stored to Salesforce successfully.");
    }

    public function syncAllQuickbooksPaymentsToSalesforce()
    {
        $this->salesforceRecordService->syncAllQuickbooksPaymentsToSalesforce();

        return response()->success("QuickBooks payments had been stored to Salesforce successfully.");
    }
}
