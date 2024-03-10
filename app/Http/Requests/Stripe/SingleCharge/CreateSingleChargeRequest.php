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
            'amount' => 'required_with:paymentMethodId|string',
            'paymentDescription' => 'required_with:paymentMethodId|string',
            'donationFormId' => 'required|string|exists:donation_forms,id',
            'name' => 'required_with:paymentMethodId|string',
            'email' => 'required_with:paymentMethodId|string',
            'billingComment' => 'nullable|string',
            'anonymousDonation' => 'required_with:paymentMethodId|boolean',
            'savePaymentMethod' => 'required_with:paymentMethodId|boolean',
            'paymentIntentId' => 'required_without_all:paymentMethodId,amount|string'
        ];
    }
}
