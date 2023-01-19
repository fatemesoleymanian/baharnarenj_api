<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class AddProductToCartRequest extends FormRequest
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
    #[ArrayShape(['product_id' => "array", 'quantity' => "string[]"])] public function rules(): array
    {
        return [
            'product_id' => ['required', Rule::exists('products', 'id')],
            'quantity' => ['required', 'min:1']
        ];
    }
}
