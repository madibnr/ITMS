<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChangeRequestRequest extends FormRequest
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
            'reason' => ['sometimes', 'required', 'string'],
            'impact' => ['sometimes', 'required', 'in:Low,Medium,High'],
            'risk_level' => ['sometimes', 'required', 'in:Low,Medium,High'],
            'scheduled_date' => ['nullable', 'date'],
            'rollback_plan' => ['nullable', 'string'],
        ];
    }
}
