<?php

namespace App\Models;

use Database\Factories\TugasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['judul', 'deskripsi', 'departemen_id', 'dosen_id', 'tenggat'])]
class Tugas extends Model
{
    /** @use HasFactory<TugasFactory> */
    use HasFactory;

    protected $table = 'tugas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tenggat' => 'date',
        ];
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    /** Dosen pemberi tugas. Bisa kosong. */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /** Tugas yang tenggatnya belum lewat. */
    public function scopeAktif(Builder $query): void
    {
        $query->whereDate('tenggat', '>=', today());
    }
}
