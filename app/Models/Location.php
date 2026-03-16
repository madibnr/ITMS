<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'description',
    ];

    // ─── Relationships ─────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'location_id');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ─── Helpers ────────────────────────────────────────

    public function getFullPathAttribute(): string
    {
        $parts = [];
        $current = $this;

        while ($current) {
            array_unshift($parts, $current->name);
            $current = $current->parent;
        }

        return implode(' › ', $parts);
    }
}
