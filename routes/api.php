<?php
    use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Endpoint Groups
|--------------------------------------------------------------------------
*/
require __DIR__ . '/Endpoints/User/AuthenticationRoutes.php';
require __DIR__ . '/Endpoints/User/ProfileRoutes.php';
require __DIR__ . '/Endpoints/PaymentRoutes.php';
require __DIR__ . '/Endpoints/SalesforceRoutes.php';
require __DIR__ . '/Endpoints/TestRoutes.php';
