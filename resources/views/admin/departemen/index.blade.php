<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ringkasan Departemen</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Kode</th>
                            <th class="px-6 py-3 font-semibold">Departemen</th>
                            <th class="px-6 py-3 font-semibold text-right">Mahasiswa</th>
                            <th class="px-6 py-3 font-semibold text-right">Dosen</th>
                            <th class="px-6 py-3 font-semibold text-right">Tugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($departemens as $departemen)
                            <tr>
                                <td class="px-6 py-3 font-mono">{{ $departemen->kode }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $departemen->nama }}</td>
                                <td class="px-6 py-3 text-right">{{ $departemen->mahasiswas_count }}</td>
                                <td class="px-6 py-3 text-right">{{ $departemen->dosens_count }}</td>
                                <td class="px-6 py-3 text-right">{{ $departemen->tugas_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-6 text-gray-500">Belum ada departemen. Jalankan php artisan db:seed.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
