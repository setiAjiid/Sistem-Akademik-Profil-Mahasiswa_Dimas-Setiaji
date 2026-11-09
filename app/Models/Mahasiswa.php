<?php

namespace App\Models;

use Database\Factories\MahasiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['nrp', 'nama', 'departemen_id', 'angkatan', 'ipk', 'foto', 'is_active'])]
class Mahasiswa extends Model
{
    /** @use HasFactory<MahasiswaFactory> */
    use HasFactory;

    protected $table = 'mahasiswas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ipk' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    /** URL publik foto, atau null kalau belum ada foto. */
    public function urlFoto(): ?string
    {
        return $this->foto ? Storage::disk('public')->url($this->foto) : null;
    }

    /** Hanya mahasiswa yang tidak sedang cuti akademik. */
    public function scopeAktif(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
