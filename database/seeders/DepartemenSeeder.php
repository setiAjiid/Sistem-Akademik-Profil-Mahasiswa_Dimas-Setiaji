<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Departemen di lingkungan FTEIC ITS. updateOrCreate membuat seeder aman dijalankan berulang.
     */
    public function run(): void
    {
        $daftar = [
            'IF' => 'Teknik Informatika',
            'SI' => 'Sistem Informasi',
            'TI' => 'Teknologi Informasi',
            'TE' => 'Teknik Elektro',
            'TK' => 'Teknik Komputer',
            'TB' => 'Teknik Biomedik',
        ];

        foreach ($daftar as $kode => $nama) {
            Departemen::updateOrCreate(['kode' => $kode], ['nama' => $nama]);
        }
    }
}
