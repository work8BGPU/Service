<?php

namespace App\Http\Requests\Api\User;

use App\Models\Phone;
use App\Models\User\User;
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
            'employee_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
            'phone' => ['required', 'string', 'max:11'],
            'password' => ['required', 'confirmed', 'string'],
            'phone_id' => ['required', 'unique:users']
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->all();

        // Проверка и создание phone_id
        if ($phone = Phone::where('phone', $data['phone'])->first()) {
            $phone_id = $phone->id;
        } else {
            $phone_id = Phone::create(['phone' => $data['phone']])->id;
        }

        $this->merge(['phone_id' => $phone_id]);
    }
}
