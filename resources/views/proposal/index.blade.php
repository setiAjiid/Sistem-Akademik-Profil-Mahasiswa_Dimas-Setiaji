<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proposal Saya</h2>
            @can('create', App\Models\Proposal::class)
                <a href="{{ route('proposal.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Buat Proposal
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <x-status-banner tipe="success" bisa-ditutup>{{ session('status') }}</x-status-banner>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg divide-y divide-gray-100">
                @forelse ($proposals as $proposal)
                    <div class="p-6 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div class="space-y-1">
                            <h3 class="font-semibold text-gray-900">{{ $proposal->judul }}</h3>
                            <p class="text-sm text-gray-600">{{ $proposal->deskripsi }}</p>
                            @if ($proposal->sudahDinilai())
                                <p class="text-sm">
                                    <span class="font-semibold text-green-700">Nilai: {{ $proposal->nilai }}</span>
                                    @if ($proposal->catatan)
                                        <span class="text-gray-500">&middot; {{ $proposal->catatan }}</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-sm text-gray-400">Belum dinilai.</p>
                            @endif
                        </div>
                        {{-- Tombol ubah hanya muncul untuk pembuat proposal (Policy update). --}}
                        @can('update', $proposal)
                            <a href="{{ route('proposal.edit', $proposal) }}" class="text-sm font-semibold text-indigo-600 hover:underline">Ubah</a>
                        @endcan
                    </div>
                @empty
                    <p class="p-6 text-gray-500">Kamu belum membuat proposal. Klik "Buat Proposal" untuk memulai.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
