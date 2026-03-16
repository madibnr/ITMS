<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RootCauseAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'title',
        'root_cause',
        'contributing_factors',
        'corrective_action',
        'preventive_action',
        'analyzed_by',
        'status',
    ];

    // ─── Relationships ─────────────────────────────────

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class , 'analyzed_by');
    }
}
