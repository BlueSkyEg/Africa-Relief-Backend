<?php

namespace App\Http\Controllers\Salesforce;

use App\Http\Controllers\Controller;
use App\Services\Salesforce\SalesforceService;

class SalesforceAuthController extends Controller
{
    public function __construct(
        protected SalesforceService $SalesforceService,
    ){}
    
    public function getToken(){
        return $this->SalesforceService->authenticate();
    }
}
