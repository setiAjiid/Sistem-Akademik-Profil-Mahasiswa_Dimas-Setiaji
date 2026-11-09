<?php

namespace App\Models;

use Database\Factories\DepartemenFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'nama'])]
class Departemen extends Model
{
    /** @use HasFactory<DepartemenFactory> */
    use HasFactory;

    protected $table = 'departemens';

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosens(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }
}
