<?php

namespace App\Modules\Stripe\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class attachPaymentMethodRequest extends FormRequest
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
            'stripeCustomerId' => 'required|string'
        ];
    }
}
