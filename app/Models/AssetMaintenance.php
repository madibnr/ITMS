<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenance';

    protected $fillable = [
        'asset_id',
        'maintenance_type',
        'description',
        'vendor',
        'cost',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
}
