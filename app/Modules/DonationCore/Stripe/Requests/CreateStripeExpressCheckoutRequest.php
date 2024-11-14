<?php

namespace App\Modules\DonationCore\Stripe\Requests;

use App\Enums\StripeRecurringPeriods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStripeExpressCheckoutRequest extends FormRequest
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
            'recurringPeriod' => ['nullable', Rule::in(StripeRecurringPeriods::cases())],
            'coverFees' => 'nullable|boolean',
        ];
    }
}
