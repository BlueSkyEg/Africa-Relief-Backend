<?php

namespace App\Http\Requests\Stripe\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class RetreivePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'paymentMethodId' => 'required|string'
        ];

        // Check if the user is not authenticated, then add validation for customerId
        if (!JWTAuth::user()) {
            $rules['customerId'] = 'required|string';
        }

        return $rules;
    }
}
