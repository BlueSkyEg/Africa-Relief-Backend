<?php

namespace App\Http\Requests\Stripe\SingleCharge;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateSingleChargeRequest extends FormRequest
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
            'paymentMethodId' => 'required_if:paymentIntentId,null|string',
            'amount' => 'required_with:paymentMethodId|numeric',
            'paymentDescription' => 'required_with:paymentMethodId|string',
            'paymentIntentId' => 'required_without_all:paymentMethodId,amount|string'
        ];

        // Check if the user is not authenticated, then add validation for customerId
        if (!JWTAuth::user()) {
            $rules['customerId'] = 'required|string';
        }

        return $rules;
    }
}
