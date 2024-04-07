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
            'name' => 'nullable|string',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($this->user())],
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'username' => ['nullable', 'string', Rule::unique('users')->ignore($this->user())]
        ];
    }
}
