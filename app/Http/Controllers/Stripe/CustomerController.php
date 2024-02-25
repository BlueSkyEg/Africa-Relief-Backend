<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stripe\Customer\CreateCustomerRequest;
use App\Services\Stripe\Customer\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Models\Donor;
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
            // If Authed User Get his stripe_customer_id
            $user = JWTAuth::user();
            if ($user) {
                return $this->successResponse('customer id retrieved successfully', ['customer_id' => $user->donor->stripe_customer_id]);
            }

            // If Guest & Existing Donor 
            $donor = Donor::where("email", $request->email)->first();
            if($donor) {
                return $this->successResponse('customer id retrieved successfully', ['customer_id' => $donor->stripe_customer_id]);
            }

            // If Guest & New Donor 
            $stripe_customer_id = $this->customerService->create($request);
            return $this->successResponse('customer id retrieved successfully', ['customer_id' => $stripe_customer_id]);
        
        } catch (\Exception $e) {
            return $this->errorResponse('error ' . $e->getMessage());
        }
    }
}
