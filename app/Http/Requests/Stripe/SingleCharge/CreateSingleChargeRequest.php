<?php

namespace App\Http\Requests\Stripe\SingleCharge;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'paymentMethodId' => 'required_if:paymentIntentId,null|string',
            'amount' => 'required_with:paymentMethodId|numeric',
            'paymentDescription' => 'required_with:paymentMethodId|string',
            'projectId' => 'required_with:paymentMethodId|string',
            'firstName' => 'required_with:paymentMethodId|string',
            'lastName' => 'required_with:paymentMethodId|string',
            'email' => 'required_with:paymentMethodId|string',
            'billingComment' => 'required_with:paymentMethodId|string',
            'anonymousDonation' => 'required_with:paymentMethodId|in:0,1',
            'paymentIntentId' => 'required_without_all:paymentMethodId,amount|string'
        ];
    }
}
