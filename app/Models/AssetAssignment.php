<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'assigned_to_user_id',
        'assigned_by',
        'assigned_date',
        'expected_return_date',
        'returned_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'expected_return_date' => 'date',
            'returned_date' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereNull('returned_date');
    }

    public function scopeReturned($query)
    {
        return $query->whereNotNull('returned_date');
    }

    // ─── Helpers ────────────────────────────────────────

    public function isActive(): bool
    {
        return is_null($this->returned_date);
    }
}
