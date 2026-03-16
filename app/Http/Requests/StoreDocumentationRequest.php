<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'manager', 'it-staff']);
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['required', 'exists:documentation_categories,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'content'      => ['required', 'string'],
            'attachment'   => ['nullable', 'file', 'mimes:pdf,docx,xlsx,png,jpg,jpeg', 'max:10240'],
            'status'       => ['required', 'in:draft,published'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:documentation_tags,id'],
            'meta'         => ['nullable', 'array'],
            'meta.*'       => ['nullable', 'string', 'max:1000'],
        ];
    }
}
