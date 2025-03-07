<?php

namespace App\Http\Requests;

use App\Models\Complex;
use App\Models\CourtTypeSurfaceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CourtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && Complex::where('id', $this->complex_id)->where('company_id', $this->user()->company_id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'complex_id' => ['required', 'int', Rule::exists('complexes', 'id')],
            'court_type_id' => ['required', 'int', Rule::exists('court_types', 'id')],
            'surface_type_id' => ['required', 'int', Rule::exists('surface_types', 'id')],
            'name' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string', 'max:255'],
            'hourly_rate' => ['required', 'numeric', 'min:1'],
            'divisible' => ['required', 'boolean'],
            'max_divisions' => [
                Rule::requiredIf(fn() => (bool)$this->divisible),
                'int',
                'min:0',
            ],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->opening_time && $this->closing_time) {
                $openingTime = Carbon::createFromFormat('H:i', $this->opening_time);
                $closingTime = Carbon::createFromFormat('H:i', $this->closing_time);

                if ($openingTime->greaterThanOrEqualTo($closingTime)) {
                    $validator->errors()->add('closing_time', 'Closing time must be after the opening time.');
                }
            }

            if (!CourtTypeSurfaceType::where('court_type_id', $this->court_type_id)
                ->where('surface_type_id', $this->surface_type_id)
                ->exists()) {
                $validator->errors()->add('surface_type_id', 'The selected surface type is not valid for the chosen court type.');
            }
        });
    }
}
