<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentationCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'unique:documentation_categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:100'],
        ];
    }
}
