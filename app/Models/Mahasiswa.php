<?php

namespace App\Models;

use Database\Factories\MahasiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nrp', 'nama', 'departemen_id', 'angkatan', 'ipk', 'is_active'])]
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

    /** Hanya mahasiswa yang tidak sedang cuti akademik. */
    public function scopeAktif(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
