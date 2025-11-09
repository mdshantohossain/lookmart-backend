<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'email' => ['required', 'email', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
            'phone' => ['nullable', 'unique:users,phone', 'regex:/^(?:\+8801|01)[3-9]\d{8}$/'],
        ];

        if($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'][] = Rule::unique('users', 'email')->ignore($this->route('user')?->id);
            $rules['phone'][] = Rule::unique('users', 'phone')->ignore($this->route('user')?->id);
            $passwordRule = 'nullable|min:6';
        } else {
            $rules['email'][] = 'unique:users,email';
            $rules['phone'][] = 'unique:users,phone';
            $passwordRule = 'required|min:6';
        }

        return [
            'name' => 'required|min:2|max:28',
            'email' => $rules['email'],
            'password' => $passwordRule,
            'phone' => $rules['phone'],
            'role' => 'required|exists:roles,name',
        ];
    }
}
