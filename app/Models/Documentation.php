<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Documentation extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'content',
        'attachment',
        'attachment_original_name',
        'created_by',
        'updated_by',
        'status',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($doc) {
            if (empty($doc->slug)) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }
        });

        static::updating(function ($doc) {
            if ($doc->isDirty('title') && !$doc->isDirty('slug')) {
                $doc->slug = Str::slug($doc->title) . '-' . Str::random(6);
            }
        });
    }

    // ─── Relationships ─────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentationCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            DocumentationTag::class,
            'documentation_tag_relations',
            'documentation_id',
            'tag_id'
        );
    }

    public function meta(): HasMany
    {
        return $this->hasMany(DocumentationMeta::class, 'documentation_id');
    }

    // ─── Scopes ─────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', fn($q) => $q->where('documentation_tags.id', $tagId));
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"))
              ->orWhereHas('tags', fn($t) => $t->where('name', 'like', "%{$search}%"));
        });
    }

    // ─── Helpers ────────────────────────────────────────

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment
            ? Storage::url($this->attachment)
            : null;
    }

    public function getMetaValue(string $key): ?string
    {
        $meta = $this->meta->firstWhere('key', $key);
        return $meta?->value;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
