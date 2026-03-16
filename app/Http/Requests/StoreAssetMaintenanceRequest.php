<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'maintenance_type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:Scheduled,In Progress,Completed'],
        ];
    }
}
