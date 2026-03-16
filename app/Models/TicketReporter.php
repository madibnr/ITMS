<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TicketReporter extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'full_name',
        'nik',
        'whatsapp',
        'email',
    ];

    // ─── Boot ──────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (TicketReporter $reporter) {
            if (empty($reporter->uuid)) {
                $reporter->uuid = (string)Str::uuid();
            }
        });
    }

    // ─── Relationships ─────────────────────────────────

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class , 'reporter_id');
    }

    // ─── Helpers ───────────────────────────────────────

    /**
     * Find or create a reporter by NIK and email combination.
     */
    public static function findOrCreateByNik(array $data): self
    {
        return static::firstOrCreate(
        ['nik' => $data['nik'], 'email' => $data['email']],
        [
            'full_name' => $data['full_name'],
            'whatsapp' => $data['whatsapp'],
        ]
        );
    }
}
