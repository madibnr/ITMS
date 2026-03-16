<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_id',
        'license_key',
        'seats',
        'expiration_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'expiration_date' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'license_user')
            ->withPivot('assigned_at');
    }

    // ─── Helpers ────────────────────────────────────────

    public function getAvailableSeatsAttribute(): int
    {
        return $this->seats - $this->users()->count();
    }

    public function isExpired(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiration_date
            && $this->expiration_date->isBetween(now(), now()->addDays($days));
    }
}
