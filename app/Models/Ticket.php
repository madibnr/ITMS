<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'category_id',
        'priority',
        'status',
        'user_id',
        'reporter_id',
        'assigned_to',
        'sla_deadline',
        'resolved_at',
        'resolution_note',
        'closed_by',
        'ip_address',
        'user_agent',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'sla_deadline' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class , 'closed_by');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(TicketReporter::class , 'reporter_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class , 'attachable');
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class , 'related_ticket_id');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['Open', 'In Progress']);
    }

    public function scopeHistory($query)
    {
        return $query->whereIn('status', ['Resolved', 'Closed']);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    public function scopePublic($query)
    {
        return $query->where('source', 'public');
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->whereNotIn('status', ['Resolved', 'Closed']);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    // ─── Helpers ────────────────────────────────────────

    public function isOverdue(): bool
    {
        return $this->sla_deadline
            && $this->sla_deadline->isPast()
            && !in_array($this->status, ['Resolved', 'Closed']);
    }

    public static function generateTicketNumber(): string
    {
        $date = now()->format('Ymd');
        $lastTicket = static::where('ticket_number', 'like', "TKT-{$date}-%")
            ->orderByDesc('ticket_number')
            ->first();

        $sequence = $lastTicket
            ? (int)substr($lastTicket->ticket_number, -4) + 1
            : 1;

        return sprintf('TKT-%s-%04d', $date, $sequence);
    }
}
