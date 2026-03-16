<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'Active';
    const STATUS_IN_REPAIR = 'In Repair';
    const STATUS_IN_STOCK = 'In Stock';
    const STATUS_RETIRED = 'Retired';
    const STATUS_LOST = 'Lost';
    const STATUS_BROKEN = 'Broken';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_IN_REPAIR,
        self::STATUS_IN_STOCK,
        self::STATUS_RETIRED,
        self::STATUS_LOST,
        self::STATUS_BROKEN,
    ];

    protected $fillable = [
        'asset_code',
        'asset_tag',
        'name',
        'category_id',
        'model_id',
        'brand',
        'model',
        'serial_number',
        'specifications',
        'purchase_date',
        'purchase_cost',
        'supplier',
        'warranty_expired',
        'warranty_expiration',
        'location',
        'location_id',
        'assigned_user_id',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
            'purchase_date' => 'date',
            'purchase_cost' => 'decimal:2',
            'warranty_expired' => 'date',
            'warranty_expiration' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assetModel(): BelongsTo
    {
        return $this->belongsTo(AssetModel::class, 'model_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function locationRef(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AssetHistory::class)->orderByDesc('created_at');
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class)->orderByDesc('assigned_date');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class)->orderByDesc('start_date');
    }

    public function files(): HasMany
    {
        return $this->hasMany(AssetFile::class)->orderByDesc('created_at');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AssetLog::class)->orderByDesc('created_at');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'related_asset_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWarrantyExpiringSoon($query, int $days = 30)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNotNull('warranty_expiration')
                ->whereBetween('warranty_expiration', [now(), now()->addDays($days)]);
        })->orWhere(function ($q) use ($days) {
            $q->whereNull('warranty_expiration')
                ->whereNotNull('warranty_expired')
                ->whereBetween('warranty_expired', [now(), now()->addDays($days)]);
        });
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_user_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_user_id');
    }

    // ─── Helpers ────────────────────────────────────────

    public function isWarrantyExpired(): bool
    {
        $date = $this->warranty_expiration ?? $this->warranty_expired;
        return $date && $date->isPast();
    }

    public function getWarrantyDateAttribute()
    {
        return $this->warranty_expiration ?? $this->warranty_expired;
    }

    public function getDisplayLocationAttribute(): string
    {
        if ($this->locationRef) {
            return $this->locationRef->full_path;
        }
        return $this->location ?? '-';
    }

    public function getDisplayModelAttribute(): string
    {
        if ($this->assetModel) {
            return $this->assetModel->full_name;
        }
        $parts = array_filter([$this->brand, $this->model]);
        return implode(' ', $parts) ?: '-';
    }

    /**
     * Calculate straight-line depreciation.
     * @param int $usefulLifeYears The useful life of the asset in years.
     * @return array{current_value: float, depreciation_per_year: float, age_years: float}
     */
    public function calculateDepreciation(int $usefulLifeYears = 5): array
    {
        $cost = (float) ($this->purchase_cost ?? 0);
        if ($cost <= 0 || !$this->purchase_date) {
            return ['current_value' => 0, 'depreciation_per_year' => 0, 'age_years' => 0];
        }

        $ageYears = $this->purchase_date->diffInDays(now()) / 365.25;
        $depreciationPerYear = $cost / $usefulLifeYears;
        $currentValue = max(0, $cost - ($depreciationPerYear * $ageYears));

        return [
            'current_value' => round($currentValue, 2),
            'depreciation_per_year' => round($depreciationPerYear, 2),
            'age_years' => round($ageYears, 1),
        ];
    }

    public static function generateAssetCode(string $categoryPrefix = 'GEN'): string
    {
        $lastAsset = static::where('asset_code', 'like', "AST-{$categoryPrefix}-%")
            ->orderByDesc('asset_code')
            ->first();

        $sequence = $lastAsset
            ? (int) substr($lastAsset->asset_code, -4) + 1
            : 1;

        return sprintf('AST-%s-%04d', $categoryPrefix, $sequence);
    }

    public static function generateAssetTag(): string
    {
        // Read format settings from AssetController (stored in storage/app/asset_tag_format.json)
        $fmt = \App\Http\Controllers\AssetController::getTagFormat();

        $prefix    = strtoupper($fmt['prefix'] ?? 'ITMS');
        $separator = $fmt['separator'] ?? '-';
        $digits    = max(3, min(8, (int) ($fmt['digits'] ?? 6)));

        // If a specific next_number is saved, use it; otherwise auto-detect from DB
        if (!empty($fmt['next_number'])) {
            $sequence = (int) $fmt['next_number'];
        } else {
            $last = static::whereNotNull('asset_tag')
                ->where('asset_tag', 'like', "{$prefix}{$separator}%")
                ->orderByDesc('asset_tag')
                ->first();

            $sequence = $last
                ? (int) substr($last->asset_tag, -(int) $digits) + 1
                : 1;
        }

        return $prefix . $separator . str_pad($sequence, $digits, '0', STR_PAD_LEFT);
    }
}
