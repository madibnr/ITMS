<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class DocumentationTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && !$tag->isDirty('slug')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // ─── Relationships ─────────────────────────────────

    public function documentations(): BelongsToMany
    {
        return $this->belongsToMany(
            Documentation::class,
            'documentation_tag_relations',
            'tag_id',
            'documentation_id'
        );
    }
}
