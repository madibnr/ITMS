<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'action',
        'description',
        'performed_by',
        'old_values',
        'new_values',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class , 'performed_by');
    }
}
