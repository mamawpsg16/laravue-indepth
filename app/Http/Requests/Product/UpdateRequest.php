<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'image' => ['nullable'],
            'name' => ['required', Rule::unique('products')->ignore($this->product->id)],
            'description' => ['nullable'],
            'price' => ['sometimes','numeric'],
            'quantity' => ['sometimes','nullable','numeric'],
        ];
    }
}
