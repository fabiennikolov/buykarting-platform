<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'condition' => ['required', 'string', 'in:new,like_new,used,needs_repair'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'currency' => ['required', 'string', 'in:eur,bgn,usd'],
            'country' => ['required', 'string', 'max:100'],
            'state_province' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'additional_images' => ['nullable', 'array', 'max:4'],
            'additional_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'remove_media' => ['nullable', 'array'],
            'remove_media.*' => ['integer'],
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
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Please select a valid category.',
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
            'main_image.image' => 'The main image must be an image file.',
            'main_image.mimes' => 'The main image must be a file of type: jpeg, png, jpg, gif, webp.',
            'main_image.max' => 'The main image may not be greater than 10MB.',
            'additional_images.max' => 'You can upload a maximum of 4 additional images.',
            'additional_images.*.image' => 'Each additional image must be an image file.',
            'additional_images.*.mimes' => 'Each additional image must be a file of type: jpeg, png, jpg, gif, webp.',
            'additional_images.*.max' => 'Each additional image may not be greater than 10MB.',
        ];
    }
}
