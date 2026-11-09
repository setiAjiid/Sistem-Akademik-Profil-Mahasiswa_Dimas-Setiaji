<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi database dengan akun uji coba dan beberapa proposal contoh.
     * Semua akun memakai password "password".
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            DepartemenSeeder::class,
            DataAkademikSeeder::class,
        ]);

        // Dosen memegang role Spatie "admin" (akses penuh), asdos memegang "staf" (tanpa hak hapus).
        $dosen = User::factory()->dosen()->create([
            'name' => 'Dosen Uji Coba',
            'email' => 'dosen@example.com',
        ]);
        $dosen->assignRole(User::PANEL_ADMIN);

        User::factory()->asdos()->create([
            'name' => 'Asdos Uji Coba',
            'email' => 'asdos@example.com',
        ])->assignRole(User::PANEL_STAF);

        $mahasiswa = User::factory()->create([
            'name' => 'Mahasiswa Uji Coba',
            'email' => 'mahasiswa@example.com',
        ]);

        // Satu proposal sudah dinilai, satu lagi belum, supaya dua tampilan langsung terlihat saat demo.
        Proposal::factory()->for($mahasiswa, 'pembuat')->dinilai($dosen)->create([
            'judul' => 'TokenPilot: pengatur konteks untuk AI coding',
        ]);

        Proposal::factory()->for($mahasiswa, 'pembuat')->create([
            'judul' => 'Database Health Checker Agent',
        ]);
    }
}
