<?php

namespace App\Console\Commands;

use App\Models\QuickbooksTransaction;
use App\Modules\SiteOptions\Services\GetSiteOptionService;
use App\Modules\SiteOptions\Services\UpdateSiteOptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

// Set QuickBooks API timeout to 30 seconds
define('QUICKBOOKS_API_TIMEOUT', 30);

class QuickbooksDataSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:quickbooks-data-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Quickbooks transaction with donors donations';

    /**
     * Execute the console command.
     * @throws ServiceException
     * @throws SdkException
     */
    public function handle(GetSiteOptionService $getSiteOptionService, UpdateSiteOptionService $updateSiteOptionService)
    {
        try {
            // Get QuickBooks Options
            $options = $getSiteOptionService->getQuickbooksOptions();

            // Refresh OAuth2 Tokens
            $oauth2LoginHelper = new OAuth2LoginHelper(env('QUICKBOOKS_CLIENT_ID'),env('QUICKBOOKS_CLIENT_SECRET'));
            $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($options['quickbooks_refresh_token'][0]->value);
            $updateSiteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
            $updateSiteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());

            // Configure DataService
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
                'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
                'refreshTokenKey' => $accessTokenObj->getRefreshToken(),
                'accessTokenKey' => $accessTokenObj->getAccessToken(),
                'QBORealmID' => $options['quickbooks_realm_id'][0]->value,
                'baseUrl' => env('QUICKBOOKS_BASE_URL')
            ]);

            $dataService->throwExceptionOnError(true);

            $latestPaidInvoiceTime = \Carbon\Carbon::parse(QuickbooksTransaction::max('last_updated_time'))->format('Y-m-d\TH:i:s-07:00');

            foreach (['Invoice', 'SalesReceipt'] as $entity) {
                $startPosition = 1;
                $transactions = $dataService->Query("Select * from $entity where Balance = '0' AND MetaData.LastUpdatedTime > " . "'" . $latestPaidInvoiceTime . "'", $startPosition);

                while ($transactions !== null) {
                    foreach ($transactions as $transaction) {
                        QuickbooksTransaction::create([
                            'quickbooks_id' => $transaction->Id,
                            'doc_number' => $transaction->DocNumber,
                            'txn_date' => $transaction->TxnDate,
                            'due_date' => $transaction->DueDate,
                            'currency' => $transaction->CurrencyRef,
                            'total_amount' => $transaction->TotalAmt,
                            'description' => $transaction->Line[0]->Description,
                            'customer_memo' => $transaction->CustomerMemo,
                            'billing_email' => $transaction->BillEmail?->Address,
                            'billing_address_1' => $transaction->BillAddr?->Line1,
                            'billing_address_2' => $transaction->BillAddr?->Line2,
                            'billing_city' => $transaction->BillAddr?->City,
                            'billing_country' => $transaction->BillAddr?->Country,
                            'billing_postal_code' => $transaction->BillAddr?->PostalCode,
                            'payment_method_ref' => $transaction->PaymentMethodRef,
                            'created_time' => $transaction->MetaData?->CreateTime,
                            'last_updated_time' => $transaction->MetaData?->LastUpdatedTime,
                            'type' => $entity
                        ]);
                    }

                    $startPosition += 100;
                    $transactions = $dataService->Query("Select * from $entity where Balance = '0' AND MetaData.LastUpdatedTime > " . "'" . $latestPaidInvoiceTime . "'", $startPosition);
                }
            }
        } catch (\Exception $e) {
            Log::error('QuickBooks Cron Job Error Message: ' . $e->getMessage() . ', With Status Code: ' . $e->getCode());
        }
    }
}
