<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CategoryRequest extends FormRequest
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
     * @return array
     */
    #[ArrayShape(['title' => "string", 'image' => "string[]"])] protected function postRules(): array
    {
        return [
            'title' => 'required',
        ];
    }

    /**
     * @return array
     */ protected function putRules(): array
    {
        return [
            'title' => 'nullable',
        ];
    }
}
