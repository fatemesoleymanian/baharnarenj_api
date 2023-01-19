<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class ContactFormRequest extends FormRequest
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

    public function rules()
    {
        return [
            'full_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'message' => 'required',

        ];    }

    public function messages()
    {
        return[
          'full_name.required' => 'لطفا نام و نام خانوادگی را وارد کنید.',
          'email.required' => 'لطفا ایمیل را وارد کنید.',
          'phone_number.required' => 'لطفا شماره تماس را وارد کنید.',
          'message.required' => 'لطفا متن پیام را وارد کنید.',
        ];
    }
}
