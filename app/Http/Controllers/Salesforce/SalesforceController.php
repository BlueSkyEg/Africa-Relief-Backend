<?php

namespace App\Http\Controllers\Salesforce;

use Illuminate\Http\Request;
use App\Services\Salesforce\SalesforceAuthService;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class SalesforceController extends Controller
{
    protected $salesforceAuthService;

    public function __construct(SalesforceAuthService $salesforceAuthService)
    {
        $this->salesforceAuthService = $salesforceAuthService;
    }

    public function generateJwtToken(){
        // Generate JWT token
        return $this->salesforceAuthService->generateJwtToken();
    }
    public function getAccounts(Request $request)
    {
        // Generate JWT token
        $jwtToken = $this->salesforceAuthService->generateJwtToken();

        // Make request to Salesforce API to fetch accounts
        $client = new Client([
            'base_uri' => env("SALESFORCE_APP_DOMAIN_NAME"),
        ]);

        try {
            $response = $client->request('GET', '/services/data/v52.0/query/?q=SELECT+Id,Name+FROM+Account', [
                'headers' => [
                    'Authorization' => 'Bearer ' . "eyJpc3MiOiAiM01WRzk5T3hUeUVNQ1EzZ05wMlBqa3FlWkt4bm1BaUcxeFY0b0hoOUFLTF9yU0suQm9TVlBHWkhRdWtYblZqelJnU3VRcUduNzVOTDd5ZmtRY3l5NyIsICJwcm4iOiAibXlAZW1haWwuY29tIiwgImF1ZCI6ICJodHRwczovL2xvZ2luLnNhbGVzZm9yY2UuY29tIiwgImV4cCI6ICIxMzMzNjg1NjI4In0=",
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
