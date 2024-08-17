<?php

namespace App\Modules\Quickbooks\Services;

use App\Modules\SiteOptions\Services\SiteOptionService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

// Set QuickBooks API timeout to 60 min
define('QUICKBOOKS_API_TIMEOUT', 60);

class QuickbooksEntityService
{
    public function __construct(private readonly SiteOptionService $siteOptionService)
    {
    }

    /**
     * This function refresh the Quickbooks access token and the refresh token
     * maybe changed so every time we refresh the tokens must save both access and refresh tokens.
     *
     * @throws SdkException
     * @throws ServiceException
     */
    public function configureDataService(): DataService
    {
        // Get QuickBooks Options
        $options = $this->siteOptionService->getQuickbooksOptions();

        // Refresh OAuth2 Tokens
        $oauth2LoginHelper = new OAuth2LoginHelper(config('quickbooks.client_id'), config('quickbooks.client_secret'));
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($options['quickbooks_refresh_token'][0]->value);
        $this->siteOptionService->updateSiteOption('quickbooks_access_token', $accessTokenObj->getAccessToken());
        $this->siteOptionService->updateSiteOption('quickbooks_refresh_token', $accessTokenObj->getRefreshToken());

        // Configure DataService
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => config('quickbooks.client_id'),
            'ClientSecret' => config('quickbooks.client_secret'),
            'refreshTokenKey' => $accessTokenObj->getRefreshToken(),
            'accessTokenKey' => $accessTokenObj->getAccessToken(),
//            'refreshTokenKey' => $options['quickbooks_refresh_token'][0]->value,
//            'accessTokenKey' => $options['quickbooks_access_token'][0]->value,
            'QBORealmID' => $options['quickbooks_realm_id'][0]->value,
            'baseUrl' => config('quickbooks.base_url')
        ]);

        $dataService->throwExceptionOnError(true);

        return $dataService;
    }

    /**
     * Get all the records of specific entity from quickbooks
     *
     * @throws SdkException
     * @throws ServiceException
     */
    public function getEntityFromQuickbooks(string $entity): int|array
    {
        //$statement = "SELECT COUNT" . "(" . "*" . ")" . "FROM $entity";
        $statement = "SELECT * FROM $entity";
        return $this->configureDataService()->Query($statement);
    }
}
