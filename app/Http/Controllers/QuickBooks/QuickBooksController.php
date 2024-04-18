<?php

namespace App\Http\Controllers\QuickBooks;

use App\Http\Controllers\Controller;
use App\Mail\Quickbooks\ReauthorizeReminder;
use App\Modules\SiteOptions\Services\GetSiteOptionService;
use App\Modules\SiteOptions\Services\UpdateSiteOptionService;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\SdkExceptions\InvalidParameterException;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\ReportService\ReportService;

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
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => env('QUICKBOOKS_SCOPE'),
            'baseUrl' => env('QUICKBOOKS_BASE_URL')
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // Generate authorization URL
        $authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
//        return response()->json($authorizationUrl);
        // Send authorization URL email
        Mail::to(env('QUICKBOOKS_REAUTHORIZE_REMINDER_EMAIL'))
            ->cc(['abdelrahman@bluskyint.com', 'support@bluskyint.com'])
            ->send(new ReauthorizeReminder($authorizationUrl));
    }

    public function handleCallback(Request $request)
    {
        try {
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
                'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
                'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
                'scope' => env('QUICKBOOKS_SCOPE'),
                'baseUrl' => env('QUICKBOOKS_BASE_URL')
            ));

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
    public function getTransactions(string $entity)
    {
        // Get QuickBooks Options
        $options = $this->getSiteOptionService->getQuickbooksOptions();

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
        $allTransactions = $dataService->Query("Select * from $entity");

        // Get dataService last error if exists
        $error = $dataService->getLastError();

        if ($error && $error->getHttpStatusCode() === 401) {
            // Refresh OAuth2 Tokens
            $oauth2LoginHelper = new OAuth2LoginHelper(env('QUICKBOOKS_CLIENT_ID'),env('QUICKBOOKS_CLIENT_SECRET'));
            $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($options['quickbooks_refresh_token'][0]->value);
            $this->updateSiteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
            $this->updateSiteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());

            // Update DataService with the new access token
            $dataService->updateOAuth2Token($accessTokenObj);

            // Get data again
            $allTransactions = $dataService->Query("Select * from $entity");
            return response()->json($allTransactions);
        } else {
            return response()->json($allTransactions);
        }
    }
}
