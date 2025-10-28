<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required',
            'regular_price' => 'nullable|numeric|min:0|max:999999999.99',
            'selling_price' => 'required|numeric|min:0|max:999999999.99',
            'suggest_price' => 'nullable|string',
            'buy_price' => 'nullable|string',
            'discount' => 'nullable|string',
            'cj_id' => 'string|nullable',
            'short_description' => 'string|required',
            'long_description' => 'string|nullable',
            'tags' => 'string|nullable',
            'sizes' => 'string|nullable',
            'variants_title' => 'string|nullable',
            'sku' => 'required|string|unique:products,sku',
            'status' => 'required|in:0,1',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
            'product_owner' => 'required|in:0,1,2',
            'is_featured' => 'string|nullable',
            'is_trending' => 'string|nullable',
            'variants' => 'nullable|array',
            'main_image' => 'required',
            'other_images' => [
                'required',
                'array', // make sure it's an array
            ],
            'other_images.*' => [
                function ($attribute, $value, $fail) {
                    // $value can be UploadedFile OR string (URL)
                    if (!$value instanceof \Illuminate\Http\UploadedFile && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Each other image must be either an uploaded image or a valid URL.');
                    }
                },
            ],
            'color_images' => 'nullable|array',
            'color_images.*' => [
                function ($attribute, $value, $fail) {
                    if (!$value instanceof \Illuminate\Http\UploadedFile && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('The main image must be either an uploaded image or a valid URL.');
                    }
                },
            ],
        ];

        if($this->isMethod('put') || $this->isMethod('patch')) {

        } else {

        }

        if ($this->input('product_owner') == '0') {
            $rules['quantity'] = 'required|string';
        } else {
            $rules['quantity'] = 'nullable|string';
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
