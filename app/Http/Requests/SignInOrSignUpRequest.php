<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInOrSignUpRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:8|max:16',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'street_address' => 'required',
            'zipcode' => 'required',

        ];
    }
}
