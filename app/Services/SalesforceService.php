<?php

namespace App\Services\Salesforce;

use GuzzleHttp\Client;

class SalesforceService
{
    public function authenticate()
    {
        $client = new Client();

        // Salesforce OAuth Endpoint
        $url = 'https://login.salesforce.com/services/oauth2/token';

        // Request body
        $params = [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => env("SALESFORCE_CLIENT_ID"),
                'client_secret' => env("SALESFORCE_CLIENT_SECRET"),
                'username'      => env("SALESFORCE_USERNAME"),
                'password'      => env("SALESFORCE_PASSWORD") . env("SALESFORCE_SECURITY_TOKEN"),
            ],
        ];

        try {
            // Authenticate with Salesforce
            $response = $client->post($url, $params);
            return json_decode($response->getBody(), true)['access_token'];
        } catch (\Exception $e) {
            // Handle authentication errors
            throw new \Exception('Salesforce authentication failed: ' . $e->getMessage());
        }
    }
}
