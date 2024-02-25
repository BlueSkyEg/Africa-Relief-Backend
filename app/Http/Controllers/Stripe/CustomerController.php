<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\Customer\CreateCustomerRequest;
use App\Services\Stripe\Customer\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    public function __construct(public CustomerService $customerService)
    {
    }

    // create stripe customer
    public function createCustomer(CreateCustomerRequest $request): JsonResponse
    {
        try {

            $user = JWTAuth::user();
            if (!$user) {
                $stripe_customer_id = $this->customerService->create($request);
                return $this->successResponse('customer id retrieved successfully', ['customer_id' => $stripe_customer_id]);
            }

            return $this->successResponse('customer id retrieved successfully', ['customer_id' => $user->donor->stripe_customer_id]);
        } catch (\Exception $e) {
            return $this->errorResponse('error ' . $e->getMessage());
        }
    }
}
