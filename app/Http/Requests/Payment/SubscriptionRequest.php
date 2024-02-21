<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'period'    => 'required|string|max:20',
            'frequency' => 'required|integer',
            'amount'    => 'required|numeric',
        ];
    }
}
