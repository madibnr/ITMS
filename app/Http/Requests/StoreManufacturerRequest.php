<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManufacturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
