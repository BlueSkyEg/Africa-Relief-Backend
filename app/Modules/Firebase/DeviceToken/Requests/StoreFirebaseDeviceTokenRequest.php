<?php

namespace App\Modules\Firebase\DeviceToken\Requests;

use App\Modules\Firebase\DeviceToken\FirebaseDeviceToken;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFirebaseDeviceTokenRequest extends FormRequest
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
            'token' => [
                'required',
                'string',
                Rule::unique(FirebaseDeviceToken::class)
            ]
        ];
    }
}
