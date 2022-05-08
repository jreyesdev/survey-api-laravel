<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Prepare the data for validation
     * @return array
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(Str::lower($this->name)),
            'email' => trim(Str::lower($this->email)),
            'password' => $this->password
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ];
    }
}
