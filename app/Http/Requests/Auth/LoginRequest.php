<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    /**
     * Prepare the data for validation
     * @return array
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => trim(Str::lower($this->email)),
            'password' => $this->password,
            'remember' => $this->remember
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return void
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255|exists:users,email',
            'password' => 'required|max:255',
            'remerber' => 'boolean'
        ];
    }

    /**
     * Mensajes de errores de los campos
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.exists' => 'The provided credentials are not correct'
        ];
    }
}
