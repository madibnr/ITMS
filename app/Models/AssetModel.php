<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_models';

    protected $fillable = [
        'manufacturer_id',
        'model_name',
        'category_id',
        'image',
        'specs',
        'default_warranty_months',
    ];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'default_warranty_months' => 'integer',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'model_id');
    }

    // ─── Helpers ────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return ($this->manufacturer?->name ? $this->manufacturer->name . ' ' : '') . $this->model_name;
    }
}
