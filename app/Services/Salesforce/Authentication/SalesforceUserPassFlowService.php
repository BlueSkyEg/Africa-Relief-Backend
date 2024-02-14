<?php

namespace App\Services\Salesforce\Authentication;


use GuzzleHttp\Client;

class SalesforceUserPassFlowService
{
    public function authenticate()
    {
        // Salesforce requires the password and security token concatenated
        $passwordWithToken = env('SALESFORCE_PASSWORD') . env('SALESFORCE_SECURITY_TOKEN');

        // Prepare login request payload
        $payload = [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => env('SALESFORCE_CLIENT_KEY'),
                'client_secret' => env('SALESFORCE_CONSUMER_SECRET'),
                'username'      => env('SALESFORCE_USERNAME'),
                'password'      => $passwordWithToken,
            ],
        ];

        // Make login request to Salesforce
        $client = new Client();
        $response = $client->post('https://login.salesforce.com/services/oauth2/token', $payload);

        return json_decode($response->getBody()->getContents(), true);
    }
}
