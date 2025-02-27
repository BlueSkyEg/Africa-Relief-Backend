<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
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
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'resume' => 'required|file|max:1000|mimes:pdf',
            'coverLetter' => 'required|string',
            'careerSlug' => 'required|string|exists:careers,slug',
        ];
    }
}
