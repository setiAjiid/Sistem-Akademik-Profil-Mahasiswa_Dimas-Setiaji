<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Portal Akademik
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            {{-- Pesan dari redirect (misalnya ditolak middleware cek.peran). --}}
            @if (session('error'))
                <x-status-banner tipe="error" judul="Akses ditolak" bisa-ditutup>{{ session('error') }}</x-status-banner>
            @endif
            @if (session('status'))
                <x-status-banner tipe="success" bisa-ditutup>{{ session('status') }}</x-status-banner>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p class="text-lg font-semibold">Halo, {{ Auth::user()->name }}.</p>
                    <p>
                        Kamu masuk sebagai
                        <span class="inline-block rounded-full bg-indigo-100 px-2 py-0.5 text-sm font-semibold text-indigo-700">{{ Auth::user()->role }}</span>.
                    </p>

                    @if (Auth::user()->punyaPeran('mahasiswa'))
                        <p>Isi rencana proyek kelompok AI kamu di halaman proposal.</p>
                        <a href="{{ route('proposal.index') }}" class="inline-block font-semibold text-indigo-600 hover:underline">Buka Proposal Saya &rarr;</a>
                    @endif

                    @can('input-nilai')
                        <p>Tinjau proposal mahasiswa dan isi nilainya di halaman penilaian.</p>
                        <a href="{{ route('admin.proposal.index') }}" class="inline-block font-semibold text-indigo-600 hover:underline">Buka Penilaian Proposal &rarr;</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
