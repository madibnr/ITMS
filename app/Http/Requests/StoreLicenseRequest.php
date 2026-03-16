<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'software_id' => ['required', 'exists:software,id'],
            'license_key' => ['required', 'string', 'max:255'],
            'seats' => ['required', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
