<?php

namespace App\Http\Requests\API\Reservation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class IndexReservationRequest extends FormRequest
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
            'end_time' => ['sometimes', 'nullable', 'date_format:H:i']
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

        ];
    }
}
