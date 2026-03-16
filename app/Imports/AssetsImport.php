<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $categoryName = $row['category'] ?? 'GEN';
        $category = Category::where('name', $categoryName)->where('type', 'asset')->first();

        $prefix = strtoupper(substr($categoryName, 0, 3));
        $assetCode = Asset::generateAssetCode($prefix);
        $assetTag = Asset::generateAssetTag();

        return new Asset([
            'asset_code' => $assetCode,
            'asset_tag' => $assetTag,
            'name' => $row['name'],
            'category_id' => $category?->id,
            'brand' => $row['brand'] ?? null,
            'model' => $row['model'] ?? null,
            'serial_number' => $row['serial_number'] ?? null,
            'purchase_date' => $row['purchase_date'] ?? null,
            'purchase_cost' => $row['purchase_cost'] ?? null,
            'supplier' => $row['supplier'] ?? null,
            'warranty_expiration' => $row['warranty_expiration'] ?? null,
            'location' => $row['location'] ?? null,
            'status' => $row['status'] ?? Asset::STATUS_IN_STOCK,
            'notes' => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'serial_number' => 'nullable|string|unique:assets,serial_number',
            'status' => 'nullable|string',
        ];
    }
}
