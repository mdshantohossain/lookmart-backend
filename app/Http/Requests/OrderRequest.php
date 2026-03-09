<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class OrderRequest extends FormRequest
{
    public bool $emailExists = false;
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
        $rules = [];

        $userId = $this->input('user_id');

        if($userId) {
            $rules['user_id'] = 'required|exists:users,id';
            $rules['phone'] = ['required', Rule::unique('users', 'phone')->ignore($userId)];
        } else {
            $rules['name'] = 'required|string';
            $rules['email'] = [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $this->emailExists = true;
                    }
                }
            ];
            $rules['password'] = 'required|string';
            $rules['phone'] = ['required', function ($attribute, $value, $fail) {
                if ($this->emailExists) {
                    // Check if phone is provided
                    if (!$value) {
                        $fail('Phone is required field.');
                    }
                } elseif (User::where('phone', $value)->exists()) {
                    $fail('Phone has already been taken.');
                }
            }];
         }

        $rules['delivery_method'] = 'required|exists:shipping_charges,id';
        $rules['payment_method'] = 'required|in:0,1';
        $rules['delivery_address'] = 'required|string|max:500';
        $rules['order_total'] = 'required|numeric|min:0';

        $rules['products'] = 'required|array|min:1';
        $rules['products.*.product_id'] = 'required|exists:products,id';
        $rules['products.*.variant_id'] = 'required|exists:product_variants,id';
        $rules['products.*.quantity'] = 'required||integer|min:1';

        return $rules;
    }
}
