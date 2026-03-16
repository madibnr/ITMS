<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'software';

    protected $fillable = [
        'software_name',
        'vendor',
        'version',
        'category',
    ];

    // ─── Relationships ─────────────────────────────────

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    // ─── Helpers ────────────────────────────────────────

    public function getTotalSeatsAttribute(): int
    {
        return $this->licenses->sum('seats');
    }

    public function getUsedSeatsAttribute(): int
    {
        return $this->licenses->sum(fn($l) => $l->users()->count());
    }

    public function getAvailableSeatsAttribute(): int
    {
        return $this->total_seats - $this->used_seats;
    }
}
