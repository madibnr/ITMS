<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentationCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', Rule::unique('documentation_categories', 'name')->ignore($this->route('category'))],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:100'],
        ];
    }
}
