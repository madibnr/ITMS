<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentationMeta extends Model
{
    protected $table = 'documentation_meta'; // migration uses singular form

    protected $fillable = [
        'documentation_id',
        'key',
        'value',
    ];

    // ─── Relationships ─────────────────────────────────

    public function documentation(): BelongsTo
    {
        return $this->belongsTo(Documentation::class, 'documentation_id');
    }
}
