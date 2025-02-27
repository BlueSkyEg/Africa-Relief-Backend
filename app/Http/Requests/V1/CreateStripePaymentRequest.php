<?php

namespace App\Http\Requests\V1;

use App\Enums\StripeRecurringPeriods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string',
            'email' => 'required|string',
            'amount' => 'required|string',
            'donationFormId' => 'required|string|exists:donation_forms,id',
            'paymentMethodId' => 'required_without:stripePaymentMethodId|string|exists:payment_methods,id',
            'stripePaymentMethodId' => 'required_without:paymentMethodId|string',
            'anonymousDonation' => 'required|boolean',
            'savePaymentMethod' => 'required_with:stripePaymentMethodId|boolean',
            'recurringPeriod' => ['nullable', Rule::in(StripeRecurringPeriods::cases())],
            'billingComment' => 'nullable|string',
            'coverFees' => 'nullable|boolean',
            'contribution' => 'nullable',
        ];
    }
}
