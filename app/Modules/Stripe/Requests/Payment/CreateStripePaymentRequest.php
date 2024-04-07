<?php

namespace App\Modules\Stripe\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreateStripePaymentRequest extends FormRequest
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
            'amount' => 'required|string',
            'donationFormTitle' => 'required|string',
            'donationFormId' => 'required|string|exists:donation_forms,id',
            'name' => 'required|string',
            'email' => 'required|string',
            'anonymousDonation' => 'required|boolean',
            'savePaymentMethod' => 'required|boolean',
            'billingComment' => 'nullable|string',
            'recurringPeriod' => 'nullable|in:day,week,month,quarter,year',
        ];
    }
}
