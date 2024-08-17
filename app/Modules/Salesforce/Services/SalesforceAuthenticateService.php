<?php

namespace App\Modules\Salesforce\Services;

use Illuminate\Support\Facades\Http;

class SalesforceAuthenticateService
{
    public function getSalesforceAccessToken()
    {
        $response = Http::asForm()->post(config('salesforce.auth_endpoint'), [
            'grant_type' => config('salesforce.grant_type'),
            'client_id' => config('salesforce.client_id'),
            'client_secret' => config('salesforce.client_secret'),
        ]);

        return json_decode($response->body())->access_token;
    }
}
