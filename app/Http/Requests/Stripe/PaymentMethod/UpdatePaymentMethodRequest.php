<?php

namespace App\Http\Requests\Stripe\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodRequest extends FormRequest
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
            'paymentMethodId' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'address' => 'required|array:city,country,line1,line2,postal_code,state',
            'address.country' => 'required|string|size:2',
            'address.city' => 'required|string',
            'address.line1' => 'required|string',
            'address.line2' => 'nullable|string',
            'address.postal_code' => 'required|regex:/\b\d{5}\b/',
            'address.state' => 'required|string',
        ];
    }
}
