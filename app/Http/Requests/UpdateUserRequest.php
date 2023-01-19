<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'address' => "string",
        'national_code' => "string",
        'email' => "string[]",
        'mobile' => "string[]",
        'job' => "string",
        'city' => "string",
        'birthdate' => "string[]",
        'username' => "string"
    ])] public function rules(): array
    {
        return [
            'address' => 'required',
            'national_code' => 'nullable',
            'city' => 'required',
            'email' => ['required', 'email'],
            'mobile' => ['required', 'regex:/(09)[0-9]{9}/'],
            'job' => 'nullable',
            'birthdate' => ['nullable', 'date'],
            'username' => 'required'
        ];
    }
}
