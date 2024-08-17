<?php

namespace App\Console\Commands;

use App\Modules\Quickbooks\Services\QuickbooksReceiptService;
use App\Modules\Salesforce\Services\SalesforceRecordService;
use App\Modules\Salesforce\Services\SalesforceAuthenticateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class SyncQuickbooksReceiptsToSalesforceCommand extends Command
{
    public function __construct(
        private readonly QuickbooksReceiptService      $quickbooksReceiptService,
        private readonly SalesforceAuthenticateService $salesforceAuthenticateService,
        private readonly SalesforceRecordService       $salesforceRecordService,
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-quickbooks-receipts-to-salesforce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Quickbooks SalesReceipts to Database & Salesforce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastUpdatedTime = $this->quickbooksReceiptService->getReceiptsLastUpdatedTime();

        DB::beginTransaction();

        try {
            $receipts = $this->quickbooksReceiptService->syncReceiptsFromQuickbooks("WHERE MetaData.LastUpdatedTime > '$lastUpdatedTime'");

            $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

            foreach ($receipts as $receipt) {
                if ($receipt->salesforce_opportunity_id) {
                    $this->salesforceRecordService->updateSalesforceRecord('opportunity', $receipt, $accessToken);
                    $this->salesforceRecordService->updateSalesforceRecord('npe01__OppPayment__c', $receipt, $accessToken);
                } else {
                    $receiptId = $this->salesforceRecordService->createSalesforceRecord('opportunity', $receipt, $accessToken);
                    $paymentId = $this->salesforceRecordService->createSalesforceRecord('npe01__OppPayment__c', $receipt, $accessToken);

                    $receipt->salesforce_opportunity_id = $receiptId;
                    $receipt->salesforce_payment_id = $paymentId;
                    $receipt->save();
                }
            }
        } catch (SdkException|ServiceException|\Exception $e) {
            DB::rollBack();
            Log::debug('Sync Quickbooks Receipts to Salesforce Cron Job Error Message: ' . $e->getMessage() . ', With Status Code: ' . $e->getCode());
        }

        DB::commit();
    }
}
