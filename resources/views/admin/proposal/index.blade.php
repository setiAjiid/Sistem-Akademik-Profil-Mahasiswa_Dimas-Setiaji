<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penilaian Proposal</h2>
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
                            <th class="px-6 py-3 font-semibold">Judul</th>
                            <th class="px-6 py-3 font-semibold">Mahasiswa</th>
                            <th class="px-6 py-3 font-semibold">Nilai</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($proposals as $proposal)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $proposal->judul }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $proposal->pembuat->name }}</td>
                                <td class="px-6 py-3">{{ $proposal->nilai ?? 'Belum dinilai' }}</td>
                                <td class="px-6 py-3 text-right">
                                    {{-- Link input nilai disembunyikan dari user yang tidak lolos Gate. --}}
                                    @can('input-nilai')
                                        <a href="{{ route('admin.proposal.show', $proposal) }}" class="font-semibold text-indigo-600 hover:underline">Nilai</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-6 text-gray-500">Belum ada proposal masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
