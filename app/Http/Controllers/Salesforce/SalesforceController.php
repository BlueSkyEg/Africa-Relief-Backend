<?php

namespace App\Http\Controllers\Salesforce;

use Illuminate\Http\Request;
use App\Services\Salesforce\Authentication\SalesforceJWTService;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class SalesforceController extends Controller
{
    protected $SalesforceJWTService;

    public function __construct(SalesforceJWTService $SalesforceJWTService)
    {
        $this->SalesforceJWTService = $SalesforceJWTService;
    }

    public function generateJwtToken(){
        // Generate JWT token
        return $this->SalesforceJWTService->generateJwtToken();
    }

    public function getAccounts(Request $request)
    {
        // Generate JWT token
        $jwtToken = $this->SalesforceJWTService->generateJwtToken();

        // Make request to Salesforce API to fetch accounts
        $client = new Client([
            'base_uri' => env("SALESFORCE_APP_DOMAIN_NAME"),
        ]);

        try {
            $response = $client->request('GET', '/services/data/v52.0/query/?q=SELECT+Id,Name+FROM+Account', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwtToken,
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
