<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'reviews' => 'required|array|min:1',
            'reviews.*.name' => 'required|min:3|max:28',
            'reviews.*.rating' => 'required|min:1|max:5',
            'reviews.*.message' => 'required|min:3',
            'reviews.*.date' => 'nullable|date',
        ];

        if($this->is('api/*')) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }
}
