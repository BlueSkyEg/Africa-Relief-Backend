<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Services\Stripe\Customer\CreateCustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    // create stripe customer
    public function createCustomer(CreateCustomerService $createCustomerService): JsonResponse
    {
        return $createCustomerService->create();
    }
}
