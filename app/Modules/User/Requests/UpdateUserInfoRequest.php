<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserInfoRequest extends FormRequest
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
            'id' => ['sometimes', 'required', 'exists:users'],
            'name' => ['sometimes', 'required', 'string'],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($this->user())
            ],
            'phone' => ['sometimes', 'required', 'string'],
            'address' => ['sometimes', 'required', 'string'],
            'username' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('users')->ignore($this->user())
            ]
        ];
    }
}
