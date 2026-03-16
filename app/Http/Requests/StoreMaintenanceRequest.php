<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'asset_id' => ['nullable', 'exists:assets,id'],
            'maintenance_type' => ['required', 'in:Preventive,Corrective,Emergency'],
            'frequency' => ['nullable', 'in:Daily,Weekly,Monthly,Quarterly,Yearly,One-time'],
            'scheduled_date' => ['required', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
