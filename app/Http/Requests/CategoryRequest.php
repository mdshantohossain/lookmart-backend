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
        if ($this->isMethod('post')) {
            // create
            $uniqueRule = Rule::unique('categories', 'name');
        } else {
            // update
            $uniqueRule = Rule::unique('categories', 'name')->ignore($this->route('category')?->id);
        }

        return [
            'name' => [ 'required', $uniqueRule],
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg',
            'status' => [ 'required', Rule::in([0, 1])],
            'remove_image' => 'numeric|nullable',
        ];
    }
}
