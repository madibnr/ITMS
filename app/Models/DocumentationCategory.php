<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DocumentationCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ─── Relationships ─────────────────────────────────

    public function documentations(): HasMany
    {
        return $this->hasMany(Documentation::class, 'category_id');
    }

    // ─── Helpers ────────────────────────────────────────

    /**
     * Return the slug-based category type for structured meta fields.
     * Maps to predefined meta field templates.
     */
    public function getMetaFieldsAttribute(): array
    {
        return match ($this->slug) {
            'network-documentation' => [
                'device_name'  => 'Device Name',
                'ip_address'   => 'IP Address',
                'vlan'         => 'VLAN',
                'location'     => 'Location',
                'notes'        => 'Notes',
            ],
            'server-rack-documentation' => [
                'rack_name'       => 'Rack Name',
                'rack_location'   => 'Rack Location',
                'u_position'      => 'U Position',
                'device_installed'=> 'Device Installed',
            ],
            'account-documentation' => [
                'system_name'  => 'System Name',
                'account_type' => 'Account Type',
                'username'     => 'Username',
                'description'  => 'Description',
                'notes'        => 'Notes',
            ],
            'sop-standard-operating-procedure' => [
                'responsible_team'  => 'Responsible Team',
                'procedure_steps'   => 'Procedure Steps',
            ],
            default => [],
        };
    }
}
