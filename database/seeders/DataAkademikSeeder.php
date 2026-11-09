<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Database\Seeder;

class DataAkademikSeeder extends Seeder
{
    /**
     * Mengisi puluhan data dummy memakai Faker lewat factory.
     * Dijalankan setelah DepartemenSeeder, karena data ini diletakkan di departemen yang sudah ada.
     */
    public function run(): void
    {
        $departemens = Departemen::all();

        // Dosen: 4 orang per departemen.
        $departemens->each(fn (Departemen $departemen) => Dosen::factory(4)->create([
            'departemen_id' => $departemen->id,
        ]));

        $dosens = Dosen::all();

        // Mahasiswa: 60 orang, tersebar acak di semua departemen.
        Mahasiswa::factory(60)
            ->state(fn () => ['departemen_id' => $departemens->random()->id])
            ->create();

        // Tugas: 25 tugas, dosen pemberi tugas diambil dari departemen yang sama.
        Tugas::factory(25)
            ->state(function () use ($dosens) {
                $dosen = $dosens->random();

                return ['departemen_id' => $dosen->departemen_id, 'dosen_id' => $dosen->id];
            })
            ->create();
    }
}
