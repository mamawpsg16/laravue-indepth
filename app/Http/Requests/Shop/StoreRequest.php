<?php

namespace App\Http\Requests\Shop;

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
            'name' => ['required','unique:shops','min:3','max:50'],
            'description' => ['sometimes','nullable', 'string',],
            'address' => ['required','min:4','max:150'],
            'city' => ['required','min:4','max:50'],
            'state' => ['nullable'],
            'country' => ['required','min:4','max:50'],
            'zip_code' => ['required','min:4','max:5'],
            'longitude' => ['nullable'],
            'latitude' => ['nullable'],
            'contact_details' => ['required','array'],
            'contact_details.*.email' => ['required','email'],
            'contact_details.*.phone' => ['required'],
            'other_details' => ['required','array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_details' => json_decode($this->contact_details, true),
            'other_details' => json_decode($this->other_details, true),
        ]);
    }
}
