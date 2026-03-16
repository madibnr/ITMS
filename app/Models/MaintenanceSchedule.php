<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'asset_id',
        'maintenance_type',
        'frequency',
        'scheduled_date',
        'completed_date',
        'assigned_to',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'completed_date' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeUpcoming($query, int $days = 7)
    {
        return $query->where('status', 'Scheduled')
            ->whereBetween('scheduled_date', [now(), now()->addDays($days)]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'Scheduled')
            ->where('scheduled_date', '<', now());
    }
}
