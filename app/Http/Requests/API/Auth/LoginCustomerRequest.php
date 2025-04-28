<?php

namespace App\Http\Requests\API\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginCustomerRequest extends FormRequest
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
            'phone_number' => 'required|string|max:255|exists:customers,phone_number',
            'password' => ['required', Password::defaults()],
        ];
    }

    /**
     * Customize the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.required' => 'The phone number is required.',
            'phone_number.string' => 'The phone number must be a valid string.',
            'phone_number.max' => 'The phone number may not be greater than 255 characters.',
            'phone_number.exists' => 'The phone number does not exist in our system.',

            'password.required' => 'The password is required.',
        ];
    }
}
