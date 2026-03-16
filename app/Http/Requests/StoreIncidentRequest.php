<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => ['required', 'in:Low,Medium,High,Critical'],
            'related_asset_id' => ['nullable', 'exists:assets,id'],
            'related_ticket_id' => ['nullable', 'exists:tickets,id'],
            'impact_description' => ['nullable', 'string'],
            'occurred_at' => ['required', 'date'],
        ];
    }
}
