<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', 'in:go_kart,parts,accessories,consumables'],
            'condition' => ['required', 'string', 'in:new,used'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'currency' => ['required', 'string', 'in:EUR,BGN,USD'],
            'country' => ['required', 'string', 'max:100'],
            'state_province' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['sometimes', 'string', 'in:active,sold,draft'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The listing title is required.',
            'title.max' => 'The listing title may not be greater than 255 characters.',
            'description.required' => 'The description is required.',
            'description.max' => 'The description may not be greater than 5000 characters.',
            'category.required' => 'Please select a category.',
            'category.in' => 'Please select a valid category.',
            'condition.required' => 'Please select the item condition.',
            'condition.in' => 'Please select a valid condition.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'price.max' => 'The price may not be greater than 999,999.99.',
            'currency.required' => 'Please select a currency.',
            'currency.in' => 'Please select a valid currency.',
            'country.required' => 'The country is required.',
            'city.required' => 'The city is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }
}
