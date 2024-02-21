<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'         => 'required|exists:users,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'project_title'   => 'required|string|max:255',
            'amount'          => 'required|string|max:255',
        ];
    }
}
