<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class ChangeUserPasswordRequest extends FormRequest
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
        'old_password' => "string",
        'new_password' => "string[]",
        'confirm_password' => "string[]"
    ])] public function rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8']
        ];
    }
}
