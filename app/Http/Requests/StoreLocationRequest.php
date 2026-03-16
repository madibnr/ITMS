<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:location,building,floor,room'],
            'parent_id' => ['nullable', 'exists:locations,id'],
            'description' => ['nullable', 'string'],
        ];
    }
}
