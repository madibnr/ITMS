<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'maintenance_type' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', 'required', 'in:Scheduled,In Progress,Completed'],
        ];
    }
}
