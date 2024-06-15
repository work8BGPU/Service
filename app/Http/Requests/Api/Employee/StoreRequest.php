<?php

namespace App\Http\Requests\Api\Employee;

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
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'patronymic' => ['nullable', 'string', 'max:255'],
            'personal_phone' => ['required', 'string', 'max:11'],
            'work_phone' => ['required', 'string', 'max:11'],
            'number' => ['required', 'string', 'max:8'],
            'position' => ['required'],
            'sex' => ['required'],
            'shift' => ['required'],
            'area' => ['nullable'],
            'light_work' => ['nullable']
        ];
    }
}
