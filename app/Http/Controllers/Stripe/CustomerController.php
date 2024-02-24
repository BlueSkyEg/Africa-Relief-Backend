<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Services\Stripe\Customer\CreateCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    public function __construct(public CreateCustomerService $createCustomerService)
    {
    }

    // create stripe customer
    public function createCustomer(Request $request): JsonResponse
    {
        try {

            $user = JWTAuth::user();
            if (!$user) {
                $stripe_customer_id = $this->createCustomerService->create($request->email);
                return $this->successResponse('customer id retrieved successfully', ['customer_id' => $stripe_customer_id]);
            }

            return $this->successResponse('customer id retrieved successfully', ['customer_id' =>$user->donor->stripe_customer_id]);

        } catch (\Exception $e) {
            return $this->errorResponse('error ' . $e->getMessage());
        }
    }
}
