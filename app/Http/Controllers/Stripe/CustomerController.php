<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Modules\Stripe\Services\Customer\CreateCustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    // create stripe customer
    public function createCustomer(CreateCustomerService $createCustomerService): JsonResponse
    {
        return $createCustomerService->create();
    }
}
