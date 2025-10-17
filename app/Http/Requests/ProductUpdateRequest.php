<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required',
            'regular_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount' => 'string|nullable',
            'quantity' => 'string',
            'buying_price' => 'numeric|nullable',
            'cj_id' => 'numeric|nullable',
            'short_description' => 'string|required',
            'long_description' => 'string|nullable',
            'sku' => 'string|required',
            'status' => 'required'
        ];

        if($this->filled('cj_id')) {
            $rules['cj_main_image'] = 'required|string';
            $rules['cj_other_images'] = 'required|array';
            $rules['cj_other_images.*'] = 'string';
        } else {
            // Manual upload case
            $rules['main_image'] = 'nullable|image';
            $rules['other_images'] = 'nullable|array';
            $rules['other_images.*'] = 'image';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required',
            'sub_category_id.required' => 'Sub Category is required',
            'other_images.required' => 'Other image is required',
        ];
    }
}
