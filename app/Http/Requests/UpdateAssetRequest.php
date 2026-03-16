<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assetId = $this->route('asset') ? $this->route('asset')->id : null;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'model_id' => ['nullable', 'exists:asset_models,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100', 'unique:assets,serial_number,' . $assetId],
            'specifications' => ['nullable', 'array'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:150'],
            'warranty_expiration' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'status' => ['sometimes', 'required', Rule::in(Asset::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
