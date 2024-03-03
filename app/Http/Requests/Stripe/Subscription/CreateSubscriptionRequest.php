<?php

namespace App\Http\Requests\Stripe\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'recurringPeriod' => 'required|in:day,week,month,quarter,year',
            'subscriptionName' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'donationFormId' => 'required|string',
            'donorBillingComment' => 'required|string',
            'anonymousDonation' => 'required|boolean',
            'savePaymentMethod' => 'required|boolean'
        ];
    }
}
