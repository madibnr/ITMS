<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => ['required', 'exists:manufacturers,id'],
            'model_name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'specs' => ['nullable', 'array'],
            'default_warranty_months' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
