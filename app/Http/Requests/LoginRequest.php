<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required', Rule::exists('users', 'username')],
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'لطفا رمزعبور خود را وارد کنید.',
            'username.required' => 'لطفا نام کاربری خود را وارد کنید.',
        ];
    }
}
