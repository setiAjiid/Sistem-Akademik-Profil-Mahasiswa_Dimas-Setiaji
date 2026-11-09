<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Dosen</h2>
            <a href="{{ route('admin.dosen.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Dosen
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
                            <th class="px-6 py-3 font-semibold">NIP</th>
                            <th class="px-6 py-3 font-semibold">Nama</th>
                            <th class="px-6 py-3 font-semibold">Email</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($dosens as $dosen)
                            <tr>
                                <td class="px-6 py-3 font-mono">{{ $dosen->nip }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $dosen->nama }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $dosen->email ?? '-' }}</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.dosen.edit', $dosen) }}" class="font-semibold text-indigo-600 hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('admin.dosen.destroy', $dosen) }}" class="inline"
                                          onsubmit="return confirm('Hapus data {{ $dosen->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="ms-3 font-semibold text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-6 text-gray-500">Belum ada data dosen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $dosens->links() }}
        </div>
    </div>
</x-app-layout>
