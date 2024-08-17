<?php

namespace App\Modules\Quickbooks\Services;

use App\Modules\Quickbooks\Emails\QuickbooksAuthorizationMail;
use App\Modules\SiteOptions\Services\SiteOptionService;
use Illuminate\Support\Facades\Mail;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbooksAuthorizeService
{
    public function __construct(
        private readonly SiteOptionService $siteOptionService
    )
    {
    }

    /**
     * This function used one time only while authorizing the Quickbooks.
     * This function generate authorization url using Quickbooks SDK then send email to the Quickbooks admin with the url
     * that would redirect the admin to QuickBooks and ask to give the Africa Relief app access to get data from his Quickbooks account.
     * The Africa Relief app is a Quickbooks Connected App to access their APIs and get the data.
     *
     * @throws SdkException
     */
    public function sendAuthorizationMail(): void
    {
        $dataService = $this->configureDataService();

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // Generate authorization URL
        $authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        // Send authorization URL email
        Mail::to(config('quickbooks.admin_email'))
            ->send(new QuickbooksAuthorizationMail($authorizationUrl));
    }


    /**
     * This function used one time only while handling the callback after authorization to get refresh & access tokens.
     * After the Quickbooks Admin finish the authorization, Quickbooks will call this function with query params [code, realmId]
     * that will be handled to get the access token and refresh token.
     *
     * @throws ServiceException
     * @throws SdkException
     */
    public function handleCallbackAfterAuthorization($authorizationCode, $realmId): void
    {
        $dataService = $this->configureDataService();

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($authorizationCode, $realmId);

        $this->siteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
        $this->siteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());
        $this->siteOptionService->updateSiteOption('quickbooks_realm_id', $realmId);
    }


    /**
     * @throws SdkException
     */
    private function configureDataService(): DataService
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => config('quickbooks.client_id'),
            'ClientSecret' => config('quickbooks.client_secret'),
            'RedirectURI' => config('quickbooks.redirect_url'),
            'scope' => config('quickbooks.scope'),
            'baseUrl' => config('quickbooks.base_url')
        ]);

        $dataService->throwExceptionOnError(true);

        return $dataService;
    }
}
