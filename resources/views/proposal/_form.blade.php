{{-- Form bersama untuk membuat dan mengubah proposal. Variabel $proposal hanya ada saat mengubah. --}}
@csrf

<div>
    <x-input-label for="judul" value="Judul rencana proyek" />
    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
        :value="old('judul', $proposal->judul ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="deskripsi" value="Deskripsi" />
    <textarea id="deskripsi" name="deskripsi" rows="6" required
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $proposal->deskripsi ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
</div>
