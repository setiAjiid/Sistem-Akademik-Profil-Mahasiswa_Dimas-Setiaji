<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tinjau Proposal</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <x-status-banner tipe="success" bisa-ditutup>{{ session('status') }}</x-status-banner>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-lg space-y-2">
                <h3 class="text-lg font-semibold text-gray-900">{{ $proposal->judul }}</h3>
                <p class="text-sm text-gray-500">Oleh {{ $proposal->pembuat->name }}</p>
                <p class="text-gray-700 whitespace-pre-line">{{ $proposal->deskripsi }}</p>
            </div>

            @can('input-nilai')
                <form method="POST" action="{{ route('admin.proposal.nilai', $proposal) }}" class="bg-white p-6 shadow-sm sm:rounded-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nilai" value="Nilai (0-100)" />
                        <x-text-input id="nilai" name="nilai" type="number" min="0" max="100" class="mt-1 block w-32"
                            :value="old('nilai', $proposal->nilai)" required />
                        <x-input-error :messages="$errors->get('nilai')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="catatan" value="Catatan (opsional)" />
                        <textarea id="catatan" name="catatan" rows="3"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('catatan', $proposal->catatan) }}</textarea>
                        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan Nilai</x-primary-button>
                        <a href="{{ route('admin.proposal.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
                    </div>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
