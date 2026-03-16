<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'software_id' => ['sometimes', 'required', 'exists:software,id'],
            'license_key' => ['sometimes', 'required', 'string', 'max:255'],
            'seats' => ['sometimes', 'required', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
