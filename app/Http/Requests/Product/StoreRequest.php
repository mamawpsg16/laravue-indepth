<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'image' => ['image','nullable','mimes:jpeg,jpg,png'],
            'name' => ['required'],
            'description' => ['sometimes','nullable','min:10','max:200'],
            'price' => ['sometimes','numeric'],
            'quantity' => ['sometimes','nullable','numeric'],
        ];
    }
}
