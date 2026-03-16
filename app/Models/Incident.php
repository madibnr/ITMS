<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_number',
        'title',
        'description',
        'severity',
        'status',
        'reported_by',
        'assigned_to',
        'related_asset_id',
        'related_ticket_id',
        'impact_description',
        'resolution',
        'occurred_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class , 'reported_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }

    public function relatedAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class , 'related_asset_id');
    }

    public function relatedTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class , 'related_ticket_id');
    }

    public function rootCauseAnalysis(): HasOne
    {
        return $this->hasOne(RootCauseAnalysis::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class , 'attachable');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['Open', 'Investigating']);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'Critical');
    }

    // ─── Helpers ────────────────────────────────────────

    public static function generateIncidentNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::where('incident_number', 'like', "INC-{$date}-%")
            ->orderByDesc('incident_number')
            ->first();

        $sequence = $last
            ? (int)substr($last->incident_number, -4) + 1
            : 1;

        return sprintf('INC-%s-%04d', $date, $sequence);
    }
}
