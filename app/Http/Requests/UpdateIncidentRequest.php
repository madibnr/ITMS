<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'severity' => ['sometimes', 'required', 'in:Low,Medium,High,Critical'],
            'status' => ['sometimes', 'required', 'in:Open,Investigating,Resolved,Closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'resolution' => ['nullable', 'string'],
            'impact_description' => ['nullable', 'string'],
        ];
    }
}
