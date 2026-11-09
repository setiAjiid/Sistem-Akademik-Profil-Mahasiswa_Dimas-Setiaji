<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Mahasiswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" enctype="multipart/form-data" class="bg-white p-6 shadow-sm sm:rounded-lg">
                @method('PUT')
                @include('admin.mahasiswa._form')

                <div class="mt-6 flex items-center gap-4">
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
