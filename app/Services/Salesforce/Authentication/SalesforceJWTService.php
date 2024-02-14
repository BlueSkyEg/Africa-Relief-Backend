<?php

namespace App\Services\Salesforce\Authentication;

use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SalesforceJWTService
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
