<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SliderRequest extends FormRequest
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
        return $this->isMethod('post') ? $this->postRules() : $this->putRules();
    }

    /**
     * @return string[]
     */
    #[ArrayShape([
        'title' => "string",
        'image' => "string[]",
        'link' => "string"
    ])] protected function postRules(): array
    {
        return [
            'title' => 'required',
            'image' => ['required', 'mimes:jpeg,png,jpg,gif'],
            'link' => 'nullable'
        ];
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'title' => "string",
        'image' => "string[]",
        'link' => "string"
    ])] protected function putRules(): array
    {
        return [
            'title' => 'nullable',
            'image' => ['nullable', 'mimes:jpeg,png,jpg,gif'],
            'link' => 'nullable'
        ];
    }
}
