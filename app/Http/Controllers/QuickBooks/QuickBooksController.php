<?php

namespace App\Http\Controllers\QuickBooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\SdkExceptions\InvalidParameterException;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\ReportService\ReportService;

class QuickBooksController extends Controller
{
    /**
     * @throws SdkException
     * @throws InvalidParameterException
     * @throws \Exception
     */
    public function getTransactions(string $entity)
    {
        $config = config('quickbooks');

        // Configure DataService
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'accessTokenKey' => $config['access_token'],
            'refreshTokenKey' => $config['refresh_token'],
            'QBORealmID' => $config['realm_id'],
            'baseUrl' => $config['base_url']
        ));

        // Retrieve transactions
//        $allTransactions = $dataService->Query("Select * from $entity");

        $serviceContext = $dataService->getServiceContext();

        // Prep Data Services
        $reportService = new ReportService($serviceContext);

        $reportService->setStartDate("2015-01-01");
        $reportService->setAccountingMethod("Accrual");
        $reportService->setTransactionType('Deposit');
        $profitAndLossReport = $reportService->executeReport(ReportName::GENERALLEDGER);
        if (!$profitAndLossReport) {
            return response()->json("ProfitAndLossReport Is Null.");
        }

        return response()->json($profitAndLossReport);
    }
}
