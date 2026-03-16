<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'file_name',
        'file_path',
        'file_type',
        'size',
        'uploaded_by',
    ];

    // ─── Relationships ─────────────────────────────────

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ─── Helpers ────────────────────────────────────────

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }
}
