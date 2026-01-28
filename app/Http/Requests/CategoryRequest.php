<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg',
            'status' => [ 'required', Rule::in([0, 1])],
            'remove_image' => 'numeric|nullable',
        ];

        if ($this->isMethod('put')) {
            // update
            $rules['name'] = ['required', Rule::unique('categories', 'name')->ignore($this->route('category')?->id)];
        } else {
            // create
            $rules['name'] = [ 'required', Rule::unique('categories', 'name')];
        }

        return $rules;
    }
}
