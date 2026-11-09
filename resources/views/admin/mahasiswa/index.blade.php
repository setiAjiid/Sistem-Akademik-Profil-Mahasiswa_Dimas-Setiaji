<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Mahasiswa</h2>
            <a href="{{ route('admin.mahasiswa.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Mahasiswa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <x-status-banner tipe="success" bisa-ditutup>{{ session('status') }}</x-status-banner>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-6 py-3 font-semibold">NRP</th>
                            <th class="px-6 py-3 font-semibold">Nama</th>
                            <th class="px-6 py-3 font-semibold">Angkatan</th>
                            <th class="px-6 py-3 font-semibold">IPK</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mahasiswas as $mahasiswa)
                            <tr>
                                <td class="px-6 py-3 font-mono">{{ $mahasiswa->nrp }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $mahasiswa->nama }}</td>
                                <td class="px-6 py-3">{{ $mahasiswa->angkatan }}</td>
                                <td class="px-6 py-3">{{ $mahasiswa->ipk ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $mahasiswa->is_active ? 'Aktif' : 'Cuti' }}</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="font-semibold text-indigo-600 hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" class="inline"
                                          onsubmit="return confirm('Hapus data {{ $mahasiswa->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="ms-3 font-semibold text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-6 text-gray-500">Belum ada data mahasiswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $mahasiswas->links() }}
        </div>
    </div>
</x-app-layout>
