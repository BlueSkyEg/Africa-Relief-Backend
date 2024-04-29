<?php

namespace App\Http\Controllers\QuickBooks;

use App\Http\Controllers\Controller;
use App\Mail\Quickbooks\ReauthorizeReminder;
use App\Models\QuickbooksTransaction;
use App\Modules\SiteOptions\Services\GetSiteOptionService;
use App\Modules\SiteOptions\Services\UpdateSiteOptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

// Set QuickBooks API timeout to 60 seconds
define('QUICKBOOKS_API_TIMEOUT', 60);

class QuickBooksController extends Controller
{
    public function __construct(
        private readonly GetSiteOptionService $getSiteOptionService,
        private readonly UpdateSiteOptionService $updateSiteOptionService
    )
    {
    }

    public function getAuthorizationUrl()
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => env('QUICKBOOKS_SCOPE'),
            'baseUrl' => env('QUICKBOOKS_BASE_URL')
        ]);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // Generate authorization URL
        $authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        // Send authorization URL email
        Mail::to(env('QUICKBOOKS_REAUTHORIZE_REMINDER_EMAIL'))
            ->cc(['abdelrahman@bluskyint.com', 'support@bluskyint.com'])
            ->send(new ReauthorizeReminder($authorizationUrl));
    }

    public function handleCallback(Request $request)
    {
        try {
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
                'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
                'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
                'scope' => env('QUICKBOOKS_SCOPE'),
                'baseUrl' => env('QUICKBOOKS_BASE_URL')
            ]);

            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($request->query('code'), $request->query('realmId'));

            $this->updateSiteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
            $this->updateSiteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());
            $this->updateSiteOptionService->updateSiteOption('quickbooks_realm_id', $request->query('realmId'));

            return response()->api(true, 'Authorization Succeed');
        } catch (\Exception $e) {
            return response()->api(false, 'Authorization Failed');
        }
    }

    /**
     * @throws SdkException
     * @throws ServiceException
     */
    public function getTransactions()
    {
        ini_set('max_execution_time', 7200);

        // Get QuickBooks Options
        $options = $this->getSiteOptionService->getQuickbooksOptions();

        try {

            // Configure DataService
            $dataService = DataService::Configure([
                'auth_mode' => 'oauth2',
                'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
                'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
                'refreshTokenKey' => $options['quickbooks_refresh_token'][0]->value,
                'accessTokenKey' => $options['quickbooks_access_token'][0]->value,
                'QBORealmID' => $options['quickbooks_realm_id'][0]->value,
                'baseUrl' => env('QUICKBOOKS_BASE_URL')
            ]);

            $dataService->throwExceptionOnError(true);

            foreach (['Invoice', 'SalesReceipt'] as $entity) {
                    $startPosition = 1;
                    $transactions = $dataService->Query("Select * from $entity where Balance = '0'", $startPosition);
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
                        $transactions = $dataService->Query("Select * from $entity where Balance = '0'", $startPosition);
                    }
                }

            return response()->json('Mission Done');
        } catch (\Exception $e) {
            if ($e->getCode() === 401) {
                // Refresh OAuth2 Tokens
                $oauth2LoginHelper = new OAuth2LoginHelper(env('QUICKBOOKS_CLIENT_ID'),env('QUICKBOOKS_CLIENT_SECRET'));
                $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($options['quickbooks_refresh_token'][0]->value);
                $this->updateSiteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
                $this->updateSiteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());

                $this->getTransactions();
            }

            Log::error('QuickBooks Error Message: ' . $e->getMessage() . ', With Status Code: ' . $e->getCode());
        }
    }
}
