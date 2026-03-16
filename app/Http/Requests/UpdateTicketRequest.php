<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'priority' => ['sometimes', 'required', 'in:Low,Medium,High,Critical'],
            'status' => ['sometimes', 'required', 'in:Open,In Progress,Resolved,Closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'resolution_note' => ['nullable', 'string'],
        ];
    }
}
