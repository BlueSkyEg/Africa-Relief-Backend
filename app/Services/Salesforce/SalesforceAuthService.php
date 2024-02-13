<?php

namespace App\Services\Salesforce;

use Firebase\JWT\JWT;

class SalesforceAuthService
{
    public function generateJwtToken()
    {
        $privateKey = file_get_contents(base_path('keys/private_key.pem'));
        $payload = array(
            "iss" => env("SALESFORCE_CLIENT_ID"),
            "sub" => env("SALESFORCE_USERNAME"),
            "aud" => "https://login.salesforce.com",
            "exp" => strtotime("+1 minute")
        );
        return JWT::encode($payload, $privateKey, 'RS256');
    }
}
