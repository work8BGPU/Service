<?php

namespace App\Http\Requests\Api\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'passenger' => ['required', 'array'],
            'status' => ['required', 'array'],
            'station_departure' => ['required', 'array'],
            'station_arrival' => ['required', 'array'],
            'method' => ['required', 'array'],
            'category' => ['nullable', 'array'],
            'employees' => ['nullable', 'array'],
            'date_meet' => ['required'],
            'description_departure' => ['nullable'],
            'description_arrival' => ['nullable'],
            'information' => ['nullable'],
            'insp_m' => ['nullable', 'integer', 'min:0', 'max:20'],
            'insp_f' => ['nullable', 'integer', 'min:0', 'max:20'],
            'number_passenger' => ['required', 'integer', 'min:1', 'max:10'],
            'luggage_type' => ['nullable'],
            'luggage_weight' => ['nullable', 'decimal:0,3'],
            'need_help' => ['nullable', 'boolean'],
            'metroRouter' => ['required']
        ];
    }
}
