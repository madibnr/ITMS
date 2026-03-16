<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSoftwareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'software_name' => ['required', 'string', 'max:200'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'version' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
        ];
    }
}
