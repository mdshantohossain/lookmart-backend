<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // decode variants JSON

        if($this->has('variants_json')) {
            $variants = json_decode($this->input('variants_json'), true);

            $this->merge([
                'variants' => $variants
            ]);
        }
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
            'original_price' => 'nullable|numeric|min:0|max:999999999.99',
            'selling_price' => 'required|numeric|min:0|max:999999999.99',
            'buy_price' => 'nullable|string',
            'discount' => ['nullable', 'regex:/^\d{1,3}%$/'],
            'video_thumbnail' => 'nullable|file|mimetypes:video/*|mimes:mp4,mov,avi,wmv',
            'remove_video_thumbnail' => 'nullable|in:0,1',
            'remove_other_images' => 'nullable|string',
            'remove_variants' => 'nullable|string',
            'remove_variants.*' => 'in:0,1',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'tags' => 'string|nullable',
            'variants_title' => 'string|nullable',
            'status' => 'required|in:0,1',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
            'product_owner' => 'required|in:0,1,2',
            'product_policy_id.*' => "nullable|numeric|exists:product_policies,id",
            'is_featured' => 'nullable|in:0,1',
            'is_trending' => 'nullable|in:0,1',
            'is_free_delivery' => 'nullable|in:0,1',

            'variants_json' => 'required|string',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|numeric|exists:product_variants,id',
            'variants.*.vid' => 'nullable|string',
            'variants.*.variant_key' => 'required|string',
            'variants.*.sku' => 'nullable|string',
            'variants.*.buy_price' => 'nullable|numeric',
            'variants.*.selling_price' => 'nullable|numeric',
            'variants.*.suggested_price' => 'nullable|numeric',
            'variants.*.image' => 'nullable',
            'variant_images' => 'nullable|array',
        ];

        if($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['image_thumbnail'] = 'nullable|file|mimetypes:image/*,video/*|mimes:jpg,jpeg,png,webp';
            $rules['other_images'] = 'nullable|array';
            $rules['sku'] = ['required', 'string', Rule::unique('products', 'sku')->ignore($this->route('product')?->id)];
            $rules['cj_id'] = ['nullable', 'string', Rule::unique('products', 'cj_id')->ignore($this->route('product')?->id)];

        } else {
            if($this->filled('cj_id')) {
                $rules['image_thumbnail'] = 'required|string';

            } else {
                $rules['image_thumbnail'] = 'required|file|mimetypes:image/*|mimes:jpg,jpeg,png,webp';
            }

            $rules['cj_id'] = 'nullable|string|unique:products,cj_id';
            $rules['other_images'] = 'required|array';
            $rules['sku'] = ['required', 'string'];
        }

        $rules['quantity'] = ($this->product_owner == '0')
                                ? 'required|integer|min:0'
                                : 'nullable|integer|min:0';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category field is required',
            'sub_category_id.required' => 'Sub category field  is required',
            'other_images.required' => 'Gallery images field is required',

            // variants array
            'variants.required' => 'At least one variant is required',
            'variants.min' => 'At least one variant is required',

            // per variant fields
            'variants.*.variant_key.required' => 'Variant key field is required',

            'variants.*.buy_price.numeric' => 'Variant buy price must be a number',
            'variants.*.selling_price.numeric' => 'Variant selling price must be a number',
            'variants.*.suggested_price.numeric' => 'Variant suggested price must be a number',

        ];
    }
}
