<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'asset_id' => ['nullable', 'exists:assets,id'],
            'maintenance_type' => ['sometimes', 'required', 'in:Preventive,Corrective,Emergency'],
            'frequency' => ['nullable', 'in:Daily,Weekly,Monthly,Quarterly,Yearly,One-time'],
            'scheduled_date' => ['sometimes', 'required', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'status' => ['sometimes', 'in:Scheduled,In Progress,Completed,Cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
