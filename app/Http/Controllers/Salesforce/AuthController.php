<?php

namespace App\Http\Controllers\Salesforce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Salesforce\Authentication\SalesforceJWTService;
use App\Services\Salesforce\Authentication\SalesforceUserPassFlowService;

class AuthController extends Controller
{
    public function __construct(
        protected SalesforceJWTService $SalesforceJWTService,
        protected SalesforceUserPassFlowService $SalesforceUserPassFlowService,
    )
    {
        $this->SalesforceJWTService = $SalesforceJWTService;
    }

    public function generateJwtToken(){
        // Generate JWT token
        return $this->SalesforceJWTService->generateJwtToken();
    }
    public function userPassFlow(){
        // Generate JWT token
        return $this->SalesforceUserPassFlowService->authenticate();
    }
}
