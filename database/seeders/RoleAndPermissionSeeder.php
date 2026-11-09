<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Membuat role admin dan staf beserta permission-nya.
     * firstOrCreate membuat seeder aman dijalankan berulang.
     */
    public function run(): void
    {
        // Cache permission Spatie dikosongkan dulu supaya perubahan langsung terbaca.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'lihat-dashboard',
            'lihat-mahasiswa', 'tambah-mahasiswa', 'edit-mahasiswa', 'hapus-mahasiswa',
            'lihat-dosen', 'tambah-dosen', 'edit-dosen', 'hapus-dosen',
        ];

        foreach ($permissions as $nama) {
            Permission::firstOrCreate(['name' => $nama, 'guard_name' => 'web']);
        }

        // Admin: akses penuh, termasuk menghapus data.
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])
            ->syncPermissions($permissions);

        // Staf: boleh melihat, menambah, dan mengubah data, tetapi tidak boleh menghapus.
        Role::firstOrCreate(['name' => 'staf', 'guard_name' => 'web'])
            ->syncPermissions(array_filter($permissions, fn (string $nama) => ! str_starts_with($nama, 'hapus-')));
    }
}
