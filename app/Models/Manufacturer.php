<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'website',
        'support_email',
        'notes',
    ];

    // ─── Relationships ─────────────────────────────────

    public function assetModels(): HasMany
    {
        return $this->hasMany(AssetModel::class);
    }

    public function assets(): HasManyThrough
    {
        return $this->hasManyThrough(Asset::class, AssetModel::class, 'manufacturer_id', 'model_id');
    }
}
