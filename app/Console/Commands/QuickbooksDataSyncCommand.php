<?php

namespace App\Console\Commands;

use App\Modules\SiteOptions\Services\GetSiteOptionService;
use App\Modules\SiteOptions\Services\UpdateSiteOptionService;
use Illuminate\Console\Command;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

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
        // Get QuickBooks Options
        $options = $getSiteOptionService->getQuickbooksOptions();

        // Configure DataService
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'refreshTokenKey' => $options['quickbooks_refresh_token'][0]->value,
            'accessTokenKey' => $options['quickbooks_access_token'][0]->value,
            'QBORealmID' => $options['quickbooks_realm_id'][0]->value,
            'baseUrl' => env('QUICKBOOKS_BASE_URL')
        ));

        // Get data
        $allTransactions = $dataService->Query("Select * from Invoice", 3000);

        // Get dataService last error if exists
        $error = $dataService->getLastError();

        if ($error && $error->getHttpStatusCode() === 401) {
            // Refresh OAuth2 Tokens
            $oauth2LoginHelper = new OAuth2LoginHelper(env('QUICKBOOKS_CLIENT_ID'),env('QUICKBOOKS_CLIENT_SECRET'));
            $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($options['quickbooks_refresh_token'][0]->value);
            $updateSiteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
            $updateSiteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());

            // Update DataService with the new access token
            $dataService->updateOAuth2Token($accessTokenObj);

            // Get data again
            $allTransactions = $dataService->Query("Select * from Invoice", 3000);
            return response()->json($allTransactions);
        } else {
            return response()->json($allTransactions);
        }
    }
}
