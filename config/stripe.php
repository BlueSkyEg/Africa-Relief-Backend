<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Keys (Test Mode)
    |--------------------------------------------------------------------------
    */

    "public_key" => env('STRIPE_KEY'),
    "secret_key" => env('STRIPE_SECRET'),
    "webhook_secret_key" => env('STRIPE_WEBHOOK_SECRET'),
    "live_mode" => env('STRIPE_LIVE_MODE'),

];
