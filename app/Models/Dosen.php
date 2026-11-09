<?php

namespace App\Models;

use Database\Factories\DosenFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nip', 'nama', 'email', 'departemen_id'])]
class Dosen extends Model
{
    /** @use HasFactory<DosenFactory> */
    use HasFactory;

    protected $table = 'dosens';

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    /** Tugas yang diberikan dosen ini. */
    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }
}
