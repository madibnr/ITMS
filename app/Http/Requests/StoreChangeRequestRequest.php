<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChangeRequestRequest extends FormRequest
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
            'reason' => ['required', 'string'],
            'impact' => ['required', 'in:Low,Medium,High'],
            'risk_level' => ['required', 'in:Low,Medium,High'],
            'scheduled_date' => ['nullable', 'date'],
            'rollback_plan' => ['nullable', 'string'],
        ];
    }
}
