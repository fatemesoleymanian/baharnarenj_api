<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PostRequest extends FormRequest
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

    protected function postRules(): array
    {
        return [
            'image' => ['required'],
            'title' => 'required',
            'text' => 'nullable',
            'tags' => 'nullable',
            'slug' => ['required', Rule::unique('posts', 'slug')],

        ];
    }

    protected function putRules(): array
    {
        return [
            'image' => ['nullable'],
            'title' => 'nullable',
            'text' => 'nullable',
            'tags' => 'nullable',
            'slug' => ['required'],

        ];
    }
}
