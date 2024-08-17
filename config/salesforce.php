<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salesforce Keys (Sandbox)
    |--------------------------------------------------------------------------
    */

    "client_id"=> env('SALESFORCE_CLIENT_ID'),
    "client_secret"=> env('SALESFORCE_CLIENT_SECRET'),
    "grant_type"=> env('SALESFORCE_GRANT_TYPE'),
    "auth_endpoint"=> env('SALESFORCE_AUTH_ENDPOINT'),
    "endpoint"=> env('SALESFORCE_ENDPOINT'),

];
