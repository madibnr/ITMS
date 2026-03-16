<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'department',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function createdTickets(): HasMany
    {
        return $this->hasMany(Ticket::class , 'user_id');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class , 'assigned_to');
    }

    public function ticketComments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function assignedAssets(): HasMany
    {
        return $this->hasMany(Asset::class , 'assigned_user_id');
    }

    public function requestedChanges(): HasMany
    {
        return $this->hasMany(ChangeRequest::class , 'requested_by');
    }

    public function approvedChanges(): HasMany
    {
        return $this->hasMany(ChangeRequest::class , 'approved_by');
    }

    public function reportedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class , 'reported_by');
    }

    public function assignedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class , 'assigned_to');
    }

    // ─── Helpers ────────────────────────────────────────

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles()->whereIn('slug', $slugs)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn($q) => $q->where('slug', $slug))
            ->exists();
    }
}
