<?php

namespace App\Console\Commands;

use App\Modules\Quickbooks\Services\QuickbooksInvoiceService;
use App\Modules\Salesforce\Services\SalesforceRecordService;
use App\Modules\Salesforce\Services\SalesforceAuthenticateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class SyncQuickbooksInvoicesToSalesforceCommand extends Command
{
    public function __construct(
        private readonly QuickbooksInvoiceService      $quickbooksInvoiceService,
        private readonly SalesforceAuthenticateService $salesforceAuthenticateService,
        private readonly SalesforceRecordService $salesforceRecordService
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-quickbooks-invoices-to-salesforce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Quickbooks Invoices to Database & Salesforce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastUpdatedTime = $this->quickbooksInvoiceService->getInvoicesLastUpdatedTime();

        DB::beginTransaction();

        try {
            $invoices = $this->quickbooksInvoiceService->syncInvoicesFromQuickbooks("WHERE MetaData.LastUpdatedTime > '$lastUpdatedTime'");

            $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

            foreach ($invoices as $invoice) {
                if ($invoice->salesforce_opportunity_id) {
                    $this->salesforceRecordService->updateSalesforceRecord('opportunity', $invoice, $accessToken);
                } else {
                    $opportunityId = $this->salesforceRecordService->createSalesforceRecord('opportunity', $invoice, $accessToken);

                    $invoice->salesforce_opportunity_id = $opportunityId;
                    $invoice->save();
                }
            }
        } catch (SdkException|ServiceException|\Exception $e) {
            DB::rollBack();
            Log::debug('Sync Quickbooks Invoices to Salesforce Cron Job Error Message: ' . $e->getMessage() . ', With Status Code: ' . $e->getCode());
        }

        DB::commit();
    }
}
