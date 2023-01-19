<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class RegisterRequest extends FormRequest
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
   public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8'],
            'username' => ['required', Rule::unique('users', 'username')]
        ];
    }
    public function messages()
    {
        return [
          'email.required' => 'لطفا ایمیل خود را وارد کنید.',
          'password.required' => 'لطفا رمزعبور خود را وارد کنید.',
          'password.min' => 'رمزعبور باید حداقل شامل 8 کاراکتر باشد.',
          'username.required' => 'لطفا نام کاربری خود را وارد کنید.',
        ];
    }
}
