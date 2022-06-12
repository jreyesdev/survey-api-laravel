<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Prepare the data for validation
     * @return array
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim(Str::lower($this->title)),
            'user_id' => $this->user()->id,
            'status' => $this->status,
            'description' => $this->description ? trim(Str::lower($this->description)) : null,
            'expire_date' => $this->expire_date ? trim($this->expire_date) : null,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'expire_date' => 'nullable|date|after:tomorrow',
        ];
    }
}
