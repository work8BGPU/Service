<?php

namespace App\Http\Requests\Api\Workday;

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
            'employee' => ['required', 'array'],
            'time_work' => ['required'],
            'extra_shift' => ['nullable'],
            'employment_date' => ['required'],
            'new_time_work_date' => ['nullable'],
            'new_time_work' => ['nullable'],
            'intern' => ['nullable', 'boolean'],
            'jobless_days' => ['nullable', 'array']
        ];
    }
}
