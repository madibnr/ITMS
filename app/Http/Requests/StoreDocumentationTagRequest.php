<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentationTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:documentation_tags,name'],
        ];
    }
}
