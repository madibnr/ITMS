<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'change_number',
        'title',
        'description',
        'reason',
        'impact',
        'risk_level',
        'status',
        'requested_by',
        'approved_by',
        'scheduled_date',
        'implemented_at',
        'rollback_plan',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'implemented_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class , 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class , 'approved_by');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->whereIn('status', ['Draft', 'Submitted']);
    }

    // ─── Helpers ────────────────────────────────────────

    public static function generateChangeNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::where('change_number', 'like', "CHG-{$date}-%")
            ->orderByDesc('change_number')
            ->first();

        $sequence = $last
            ? (int)substr($last->change_number, -4) + 1
            : 1;

        return sprintf('CHG-%s-%04d', $date, $sequence);
    }
}
