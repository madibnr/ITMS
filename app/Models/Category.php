<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'type',
        'description',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopeTicketCategories($query)
    {
        return $query->where('type', 'ticket');
    }

    public function scopeAssetCategories($query)
    {
        return $query->where('type', 'asset');
    }
}
