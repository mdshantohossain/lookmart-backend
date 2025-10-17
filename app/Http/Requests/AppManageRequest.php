<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppManageRequest extends FormRequest
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
            'app_name' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'address' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'description' => 'nullable|string',
        ];
    }
}
