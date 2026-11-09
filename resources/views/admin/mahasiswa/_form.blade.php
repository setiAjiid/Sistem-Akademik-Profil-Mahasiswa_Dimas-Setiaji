{{-- Form bersama tambah dan ubah mahasiswa. $mahasiswa hanya ada saat mengubah. --}}
@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="nrp" value="NRP (10 digit)" />
        <x-text-input id="nrp" name="nrp" type="text" inputmode="numeric" maxlength="10" class="mt-1 block w-full"
            :value="old('nrp', $mahasiswa->nrp ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('nrp')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nama" value="Nama" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
            :value="old('nama', $mahasiswa->nama ?? '')" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="departemen_id" value="Departemen" />
        <select id="departemen_id" name="departemen_id" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Pilih departemen</option>
            @foreach ($departemens as $departemen)
                <option value="{{ $departemen->id }}" @selected(old('departemen_id', $mahasiswa->departemen_id ?? '') == $departemen->id)>
                    {{ $departemen->nama }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('departemen_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="angkatan" value="Angkatan" />
        <x-text-input id="angkatan" name="angkatan" type="number" class="mt-1 block w-full"
            :value="old('angkatan', $mahasiswa->angkatan ?? now()->year)" required />
        <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="ipk" value="IPK (opsional)" />
        <x-text-input id="ipk" name="ipk" type="number" step="0.01" min="0" max="4" class="mt-1 block w-full"
            :value="old('ipk', $mahasiswa->ipk ?? '')" />
        <x-input-error :messages="$errors->get('ipk')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="foto" value="Foto (opsional, JPG atau PNG, maksimal 2 MB)" />
        @if (isset($mahasiswa) && $mahasiswa->urlFoto())
            <img src="{{ $mahasiswa->urlFoto() }}" alt="Foto {{ $mahasiswa->nama }}" class="mt-2 size-20 rounded-md object-cover">
        @endif
        <input id="foto" name="foto" type="file" accept="image/png,image/jpeg"
            class="mt-2 block w-full text-sm text-gray-600 file:me-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
    </div>

    <div class="flex items-end">
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                @checked(old('is_active', $mahasiswa->is_active ?? true))>
            <span class="ms-2 text-sm text-gray-600">Mahasiswa aktif (tidak cuti)</span>
        </label>
    </div>
</div>
