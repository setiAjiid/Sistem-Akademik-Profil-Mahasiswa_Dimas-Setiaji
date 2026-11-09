<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Bentuk JSON satu mahasiswa untuk API. Hanya field yang aman dan perlu dibagikan.
 *
 * @mixin Mahasiswa
 */
class MahasiswaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nrp' => $this->nrp,
            'nama' => $this->nama,
            'angkatan' => $this->angkatan,
            'ipk' => $this->ipk,
            'status' => $this->is_active ? 'aktif' : 'cuti',
            'foto' => $this->urlFoto(),
            // whenLoaded: departemen hanya ikut kalau sudah di-eager load, jadi tidak memicu query tambahan.
            'departemen' => $this->whenLoaded('departemen', fn () => [
                'kode' => $this->departemen->kode,
                'nama' => $this->departemen->nama,
            ]),
        ];
    }
}
