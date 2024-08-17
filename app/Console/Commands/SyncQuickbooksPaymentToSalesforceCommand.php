<?php

namespace App\Console\Commands;

use App\Modules\Quickbooks\Services\QuickbooksPaymentService;
use App\Modules\Salesforce\Services\SalesforceRecordService;
use App\Modules\Salesforce\Services\SalesforceAuthenticateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class SyncQuickbooksPaymentToSalesforceCommand extends Command
{
    public function __construct(
        private readonly SalesforceAuthenticateService $salesforceAuthenticateService,
        private readonly SalesforceRecordService       $salesforceRecordService,
        private readonly QuickbooksPaymentService      $quickbooksPaymentService
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-quickbooks-payments-to-salesforce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Quickbooks Payments to Database & Salesforce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastUpdatedTime = $this->quickbooksPaymentService->getPaymentsLastUpdatedTime();

        DB::beginTransaction();

        try {
            $payments = $this->quickbooksPaymentService->syncPaymentsFromQuickbooks("WHERE MetaData.LastUpdatedTime > '$lastUpdatedTime'");

            $accessToken = $this->salesforceAuthenticateService->getSalesforceAccessToken();

            foreach ($payments as $payment) {
                if ($payment->salesforce_payment_id) {
                    $this->salesforceRecordService->updateSalesforceRecord('npe01__OppPayment__c', $payment, $accessToken);
                } else {
                    $opportunityId = $this->salesforceRecordService->createSalesforceRecord('npe01__OppPayment__c', $payment, $accessToken);

                    $payment->salesforce_payment_id = $opportunityId;
                    $payment->save();
                }
            }
        } catch (SdkException|ServiceException|\Exception $e) {
            DB::rollBack();
            Log::debug('Sync Quickbooks Payments to Salesforce Cron Job Error Message: ' . $e->getMessage() . ', With Status Code: ' . $e->getCode());
        }

        DB::commit();
    }
}
